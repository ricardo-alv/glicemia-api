<?php

namespace App\Repositories\Contracts;

use App\Models\Glucose;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

interface GlucoseRepositoryInterface
{
    public function getAll(array $data, bool $paginate = true): Collection| LengthAwarePaginator;
    public function createUpdateGlucose(array $data): Glucose;
    public function deleteGlucose(string | int $id): ?bool;
}
