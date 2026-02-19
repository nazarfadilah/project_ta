<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GambarDashboardResource extends JsonResource
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
                'nama' => $this->admin?->name_admin,
            ],
            'posisi' => $this->posisi,
            'path' => $this->path,
            'waktu_upload' => $this->waktu_upload,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
