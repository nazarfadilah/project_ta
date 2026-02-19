<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeritaResource extends JsonResource
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
            'admin' => [
                'email' => $this->admin?->email_admin,
                'nama' => $this->admin?->name_admin ?? 'Admin',
            ],
            'judul' => $this->judul,
            'isi' => $this->isi,
            'status' => $this->status,
            'tanggal_publish' => $this->tanggal_publish,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
