<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanTransaksiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email_users' => $this->email_users,
            'user' => [
                'email' => $this->user->email_users ?? null,
                'nama' => $this->user?->name_users ?? 'N/A',
                'no_telepon' => $this->user?->no_hp_users ?? 'N/A',
            ],
            'ruangan' => new RuanganResource($this->whenLoaded('ruangan')),
            'gedung' => [
                'id' => $this->ruangan?->gedung?->id,
                'nama' => $this->ruangan?->gedung?->nama,
                'lokasi' => $this->ruangan?->gedung?->lokasi,
            ],
            'nama_kegiatan' => $this->nama_kegiatan,
            'tgl_peminjaman' => $this->tgl_peminjaman,
            'tgl_pengembalian' => $this->tgl_pengembalian,
            'tgl_verifikasi' => $this->tgl_verifikasi,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'durasi_jam' => $this->calculateDurasi(),
            'status_peminjaman' => $this->status_peminjaman,
            'status_sarana' => $this->status_sarana,
            'keterangan' => $this->keterangan,
            'admin' => [
                'email' => $this->admin->email_admin ?? null,
                'nama' => $this->admin?->name_admin ?? 'N/A',
                'role' => $this->admin?->role ?? 'N/A',
            ],
            'detail_sarana' => DetailPeminjamanSaranaResource::collection($this->whenLoaded('detail_peminjaman_saranas')),
            'total_sarana_item' => $this->detail_peminjaman_saranas?->sum('jumlah') ?? 0,
            'jumlah_jenis_sarana' => $this->detail_peminjaman_saranas?->count() ?? 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function calculateDurasi()
    {
        if (!$this->waktu_mulai || !$this->waktu_selesai) {
            return 0;
        }

        $start = \Carbon\Carbon::createFromTimeString($this->waktu_mulai);
        $end = \Carbon\Carbon::createFromTimeString($this->waktu_selesai);
        
        return $end->diffInHours($start);
    }
}
