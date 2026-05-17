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
            'posisi' => $this->posisi,
            'path' => $this->path,
            'waktu_upload' => $this->waktu_upload,
            'updated_at' => $this->updated_at,
        ];
    }
}
