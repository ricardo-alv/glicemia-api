<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Glucose;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\GlucoseResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Contracts\GlucoseRepositoryInterface;

class GlucoseRepository implements GlucoseRepositoryInterface
{
    public function __construct(
        protected Glucose $entity
    ) {}

    public function getAll(array $data, bool $paginate = true): Collection | LengthAwarePaginator
    {
        $glucose_days_id = $data['glucose_days_id'] ?? '';

        $glucoses = $this->entity::query()
            ->with(['mealType', 'glucose_days'])
            ->where('glucose_days_id', $glucose_days_id)
            ->get();

        return $glucoses;
    }

    public function createUpdateGlucose(array $data): Glucose
    {
        $glucose_days_id = $data['glucose_days_id'];
        unset($data['glucose_days_id']);

        $data['glucose_days_id'] = $glucose_days_id;

        if (isset($data['id'])) {
            $glucose = $this->entity->find($data['id']);
            $glucose->update($data);
            return $glucose;
        }

        // Se for novo, checa se já existe
        $existingRecord = $this->entity
            ->where('glucose_days_id', $glucose_days_id)
            ->where('meal_type_id', $data['meal_type_id'])
            ->first();

        if ($existingRecord) {
            // já existe, retorna sem alterar
            return $existingRecord;
        }

        return $this->entity::create($data);      
    }

    public function deleteGlucose(string | int $id): ?bool
    {
        return $this->entity->where('glucose_days_id', $id)->delete();
    }
}
