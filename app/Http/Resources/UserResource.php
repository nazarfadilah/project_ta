<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email_users' => $this->email_users,
            'name_users' => $this->name_users,
            'no_hp_users' => $this->no_hp_users,
            'foto' => $this->foto,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat_users' => $this->alamat_users,
            'tanggal_lahir' => $this->tanggal_lahir,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
