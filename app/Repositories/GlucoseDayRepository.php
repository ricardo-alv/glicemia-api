<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\GlucoseDay;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\GlucoseDayRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GlucoseDayRepository implements GlucoseDayRepositoryInterface
{
    public function __construct(
        protected GlucoseDay $entity,
    ) {}

    public function getAll(array $data, bool $paginate = true): Collection | LengthAwarePaginator
    {
        $query = $this->entity->orderBy('date', 'desc');

        if (!empty($data['period_start']) && !empty($data['period_final'])) {
            $query->whereBetween('date', [$data['period_start'], $data['period_final']]);
        }

        return $paginate ? $query->paginate(30) : $query->with('glucoses')->get();
    }

    public function showGlucose(string | int $id): ?GlucoseDay
    {
        return $this->entity->find($id);
    }

    public function createGlucose(array $data): GlucoseDay
    {
        return  $this->entity->create($data);
    }

    public function updateGlucose(array $data, string | int $id): ?GlucoseDay
    {
        if (!$glucoseDay = $this->showGlucose($id))  return null;
        $glucoseDay->update($data);
        return $glucoseDay;
    }

    public function deleteGlucose(string | int $id): ?bool
    {
        if (!$glucoseDay = $this->showGlucose($id))  return null;
        // Excluir (Glucose) relacionados
        $glucoseDay->glucoses()->delete();
        // Excluir o Glucose Day
        return $glucoseDay->delete();
    }
}
