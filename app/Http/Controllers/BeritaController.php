<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BeritaController extends Controller
{
    // ─── PUBLIC ROUTES ───────────────────────────────────────────
    /**
     * Display list of published news for public
     */
    public function indexPublic()
    {
        $beritas = Berita::where('status', 'approved')
            ->orderByDesc('tanggal_publish')
            ->paginate(9);
        return view('public.main.berita', compact('beritas'));
    }

    /**
     * Display single published news for public
     */
    public function showPublic($slug)
    {
        $berita = Berita::where('status', 'approved')->where('slug', $slug)->firstOrFail();
        $related = Berita::where('status', 'approved')
            ->where('id', '!=', $berita->id)
            ->orderByDesc('tanggal_publish')
            ->limit(3)->get();
        return view('public.main.isi_berita', compact('berita', 'related'));
    }

    // ─── ADMIN ROUTES ────────────────────────────────────────────
    /**
     * Display all news for admin management
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->roleId == 3) {
            $beritas = Berita::with('user')->where('userId', $user->id)->orderByDesc('tanggal_publish')->get();
        } else {
            $beritas = Berita::with('user')->orderByDesc('tanggal_publish')->get();
        }
        return view('main.berita.index', compact('beritas'));
    }

    /**
     * Show the form for creating a new news
     */
    public function create()
    {
        return view('main.berita.form', ['berita' => null, 'mode' => 'create']);
    }

    /**
     * Store a newly created news in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:128|unique:berita,judul',
            'isi' => 'required|string',
            'tanggal_publish' => 'required|date',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'judul.required' => 'Judul harus diisi',
            'judul.max' => 'Judul maksimal 128 karakter',
            'judul.unique' => 'Judul sudah ada',
            'isi.required' => 'Isi berita harus diisi',
            'tanggal_publish.required' => 'Tanggal publikasi harus diisi',
            'tanggal_publish.date' => 'Format tanggal tidak valid',
            'gambar.required' => 'Gambar harus diupload',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Upload gambar
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/berita'), $filename);
            $validated['gambar'] = 'uploads/berita/' . $filename;
        }

        // Generate slug
        $validated['slug'] = Str::slug($validated['judul']);

        // Set default status to draft
        $validated['status'] = 'draft';

        // Add userId from authenticated user
        $validated['userId'] = Auth::id();

        Berita::create($validated);

        return redirect()->route('main.berita.index')
                       ->with('success', 'Berita berhasil ditambahkan');
    }

    /**
     * Display the specified news detail
     */
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        $user = Auth::user();
        if ($user->roleId == 3 && $berita->userId !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses ke berita ini.');
        }
        return view('main.berita.detail', compact('berita'));
    }

    /**
     * Show the form for editing the specified news
     */
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        $user = Auth::user();
        if ($user->roleId == 3 && $berita->userId !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses ke berita ini.');
        }
        return view('main.berita.form', ['berita' => $berita, 'mode' => 'edit']);
    }

    /**
     * Update the specified news in storage
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $user = Auth::user();
        if ($user->roleId == 3 && $berita->userId !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses ke berita ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:128|unique:berita,judul,' . $id . ',id',
            'isi' => 'required|string',
            'tanggal_publish' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'judul.required' => 'Judul harus diisi',
            'judul.max' => 'Judul maksimal 128 karakter',
            'judul.unique' => 'Judul sudah ada',
            'isi.required' => 'Isi berita harus diisi',
            'tanggal_publish.required' => 'Tanggal publikasi harus diisi',
            'tanggal_publish.date' => 'Format tanggal tidak valid',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle gambar upload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($berita->gambar && file_exists(public_path($berita->gambar))) {
                unlink(public_path($berita->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/berita'), $filename);
            $validated['gambar'] = 'uploads/berita/' . $filename;
        }

        // Update slug
        $validated['slug'] = Str::slug($validated['judul']);

        $berita->update($validated);

        return redirect()->route('main.berita.show', $berita->id)
                       ->with('success', 'Berita berhasil diperbarui');
    }

    /**
     * Remove the specified news from storage
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $user = Auth::user();
        if ($user->roleId == 3 && $berita->userId !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses ke berita ini.');
        }

        // Hapus gambar
        if ($berita->gambar && file_exists(public_path($berita->gambar))) {
            unlink(public_path($berita->gambar));
        }

        $berita->delete();

        return redirect()->route('main.berita.index')
                       ->with('success', 'Berita berhasil dihapus');
    }

    /**
     * Approve news for publishing
     */
    public function approve(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $berita->update([
            'status' => 'approved',
            'keterangan' => $request->input('keterangan', '')
        ]);

        return redirect()->route('main.berita.show', $berita->id)
                       ->with('success', 'Berita berhasil dipublikasikan');
    }

    /**
     * Reject news from publishing
     */
    public function reject(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $berita->update([
            'status' => 'rejected',
            'keterangan' => $request->input('keterangan', '')
        ]);

        return redirect()->route('main.berita.show', $berita->id)
                       ->with('success', 'Berita berhasil ditolak');
    }
}

