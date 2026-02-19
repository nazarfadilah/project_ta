<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RuanganResource extends JsonResource
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
            'gedung_id' => $this->gedung_id,
            'nama_ruangan' => $this->nama_ruangan,
            'nama_gedung' => $this->gedung?->nama,
            'petugas' => $this->gedung?->admins
                ->where('role', 'petugas')
                ->pluck('name_admin')
                ->toArray() ?? [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
