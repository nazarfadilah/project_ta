<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManageUserController extends Controller
{
    /**
     * Tampilkan daftar user (DataTables) - Hanya ACTIVE & INACTIVE
     */
    public function index()
    {
        $users = User::whereIn('status', ['ACTIVE', 'INACTIVE'])->get();
        return view('main.user.index', compact('users'));
    }

    /**
     * Tampilkan daftar user terblokir (SUSPENDED & SUSPENDED_PERMANENT)
     */
    public function blocked()
    {
        $users = User::whereIn('status', ['SUSPENDED', 'SUSPENDED_PERMANENT'])->get();
        return view('main.user.blocked', compact('users'));
    }

    /**
     * Tampilkan form edit user
     */
    public function edit($email)
    {
        $user = User::where('email', $email)->firstOrFail();
        return view('main.user.form', compact('user'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $email)
    {
        $user = User::where('email', $email)->firstOrFail();

        $request->validate([
            'username' => 'required|string|max:128|unique:users,username,' . $user->id,
            'email' => 'required|email|max:128|unique:users,email,' . $user->id,
            'status' => 'required|in:ACTIVE,INACTIVE,SUSPENDED,SUSPENDED_PERMANENT',
            'blocked_reason' => 'required_if:status,SUSPENDED,SUSPENDED_PERMANENT|nullable|string|max:1000',
        ], [
            'blocked_reason.required_if' => 'Alasan pemblokiran wajib diisi jika status di-blokir.',
        ]);

        $oldStatus = $user->status;
        $newStatus = $request->status;

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'status' => $newStatus,
            'blocked_reason' => in_array($newStatus, ['SUSPENDED', 'SUSPENDED_PERMANENT']) ? $request->blocked_reason : null,
        ]);

        // Send email if newly blocked
        if (in_array($newStatus, ['SUSPENDED', 'SUSPENDED_PERMANENT']) && $newStatus !== $oldStatus) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserBlockedMail($user, $request->blocked_reason));
            } catch (\Exception $e) {
                \Log::error('Failed to send blocked notification mail: ' . $e->getMessage());
            }
        }

        return redirect()->route('main.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Detail pengajuan buka blokir user
     */
    public function showBlockedRequest($id)
    {
        $user = User::findOrFail($id);
        
        $unblockRequest = \App\Models\UnblockRequest::where('user_id', $user->id)
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->first();
            
        return view('main.user.blocked_detail', compact('user', 'unblockRequest'));
    }

    /**
     * Menyetujui pengajuan buka blokir
     */
    public function approveUnblock($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'ACTIVE',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'blocked_reason' => null
        ]);

        \App\Models\UnblockRequest::where('user_id', $user->id)
            ->where('status', 'PENDING')
            ->update(['status' => 'APPROVED']);

        // Send email notification
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UnblockStatusUpdateMail($user, 'APPROVED'));
        } catch (\Exception $e) {
            \Log::error('Failed to send unblock approval email: ' . $e->getMessage());
        }

        return redirect()->route('main.users.blocked')->with('success', 'Permohonan buka blokir disetujui. Akun aktif kembali dengan password default: "password".');
    }

    /**
     * Menolak pengajuan buka blokir (blokir permanen)
     */
    public function rejectUnblock($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'SUSPENDED_PERMANENT'
        ]);

        \App\Models\UnblockRequest::where('user_id', $user->id)
            ->where('status', 'PENDING')
            ->update(['status' => 'REJECTED']);

        // Send email notification
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UnblockStatusUpdateMail($user, 'REJECTED'));
        } catch (\Exception $e) {
            \Log::error('Failed to send unblock rejection email: ' . $e->getMessage());
        }

        return redirect()->route('main.users.blocked')->with('success', 'Permohonan buka blokir ditolak. Akun diblokir secara permanen.');
    }
}
