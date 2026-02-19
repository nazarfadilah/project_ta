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
            'gedung' => [
                'id' => $this->gedung?->id,
                'nama' => $this->gedung?->nama,
            ],
            'profil' => [
                'foto' => $this->profil?->first()?->foto,
                'jenis_kelamin' => $this->profil?->first()?->jenis_kelamin,
                'alamat_users' => $this->profil?->first()?->alamat_users,
                'tanggal_lahir' => $this->profil?->first()?->tanggal_lahir,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
