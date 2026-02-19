<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfilResource extends JsonResource
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
            'email_admin' => $this->email_admin,
            'email_users' => $this->email_users,
            'foto' => $this->foto,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat_users' => $this->alamat_users,
            'tanggal_lahir' => $this->tanggal_lahir,
            'admin' => [
                'email' => $this->admin?->email_admin,
                'nama' => $this->admin?->name_admin,
            ],
            'user' => [
                'email' => $this->user?->email_users,
                'nama' => $this->user?->name_users,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
