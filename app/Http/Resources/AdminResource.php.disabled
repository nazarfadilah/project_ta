<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email_admin' => $this->email_admin,
            'name_admin' => $this->name_admin,
            'no_hp_admin' => $this->no_hp_admin,
            'role' => $this->role,
            'gedung_id' => $this->gedung_id,
            'foto' => $this->foto,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat_admin' => $this->alamat_admin,
            'tanggal_lahir' => $this->tanggal_lahir,
            'gedung' => [
                'id' => $this->gedung?->id,
                'nama' => $this->gedung?->nama,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
