<?php

namespace App\Services;

use App\Models\GlucoseDay;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\GlucoseDayRepositoryInterface;

class GlucoseDayService
{
    public function __construct(
        protected  GlucoseDayRepositoryInterface $glucoseDayRepository
    ) {}

    public function getAll(array $data): Collection |LengthAwarePaginator
    {
        return $this->glucoseDayRepository->getAll($data);
    }

    public function create(array $data): GlucoseDay
    {
        return $this->glucoseDayRepository->createGlucose($data);
    }

    public function update(array $data, string | int $id): ?GlucoseDay
    {
        return $this->glucoseDayRepository->updateGlucose($data, $id);
    }

    public function show(string | int $id): ?GlucoseDay
    {
        return $this->glucoseDayRepository->showGlucose($id);
    }

    public function delete(string | int $id): ?bool
    {
        return $this->glucoseDayRepository->deleteGlucose($id);
    }
}
