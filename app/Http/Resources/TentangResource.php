<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TentangResource extends JsonResource
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
            'nama_instansi' => $this->nama_instansi,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'kantor' => $this->kantor,
            'kordinat_x' => $this->kordinat_x,
            'kordinat_y' => $this->kordinat_y,
            'logo_instansi' => $this->logo_instansi,
            'foto_instansi' => $this->foto_instansi,
            'link_google_maps' => $this->link_google_maps,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
