<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlucoseDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "date" => formatDateBr($this->date),
            "day" => \Carbon\Carbon::parse($this->date)->format('d'),
            "description" => $this->description,
            "basal" => $this->basal,
        ];
    }
}
