<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use App\Models\GlucoseDay;

interface GlucoseDayRepositoryInterface
{
    public function getAll(array $data): Collection | LengthAwarePaginator;
    public function createGlucose(array $data): GlucoseDay;
    public function updateGlucose(array $data, string | int $id): ?GlucoseDay;
    public function showGlucose(string | int $id): ?GlucoseDay;
    public function deleteGlucose(string | int $id): ?bool;
}
