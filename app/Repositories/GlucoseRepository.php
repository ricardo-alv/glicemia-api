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
            $glucoses =  $glucose;
        } else {     
            if (!$existingRecord = $this->entity
                ->where('glucose_days_id', $glucose_days_id)
                ->where('meal_type_id', $data['meal_type_id'])
                ->exists()) {
                $glucoses =  $this->entity::create($data);
            }
        }
        return $glucoses;
    }

    public function deleteGlucose(string | int $id): ?bool
    {
        return $this->entity->where('glucose_days_id', $id)->delete();
    }
}
