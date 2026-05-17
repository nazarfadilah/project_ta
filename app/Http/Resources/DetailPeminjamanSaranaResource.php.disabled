<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPeminjamanSaranaResource extends JsonResource
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
            'peminjaman_id' => $this->peminjaman_id,
            'sarana_id' => $this->sarana_id,
            'sarana' => new SaranaResource($this->whenLoaded('sarana')),
            'peminjaman_transaksi' => [
                'id' => $this->peminjaman_transaksi?->id,
                'nama_kegiatan' => $this->peminjaman_transaksi?->nama_kegiatan,
                'tgl_peminjaman' => $this->peminjaman_transaksi?->tgl_peminjaman,
                'status' => $this->peminjaman_transaksi?->status_peminjaman,
            ],
            'jumlah' => $this->jumlah,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
