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
use Illuminate\Validation\ValidationException;

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
            ->orderBy('created_at', 'desc')
            ->get();

        return $glucoses;
    }

    public function show(string | int $id): ?Glucose
    {
        return $this->entity->find($id);
    }

    public function create(array $data): Glucose
    {
        $exists = $this->entity::where('meal_type_id', $data['meal_type_id'])
            ->where('glucose_days_id', $data['glucose_days_id'])
            ->where('report', 'yes')
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'duplicate' => 'Essa refeição já foi registrada para este dia!',
            ])->status(422);
        }

        return $this->entity::create($data);
    }

    public function update(array $data, string | int $id): ?Glucose
    {
        if (!$glucose = $this->show($id))  return null;

        if (
            isset($data['report']) && $data['report'] === 'yes' &&
            isset($data['meal_type_id']) && isset($data['glucose_days_id'])
        ) {
            $exists = $this->entity::where('meal_type_id', $data['meal_type_id'])
                ->where('glucose_days_id', $data['glucose_days_id'])
                ->where('report', 'yes')
                ->where('id', '!=', $id) // exclui o próprio registro da verificação
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'duplicate' => 'Essa refeição já foi registrada para este dia!',
                ])->status(422);
            }
        }

        $glucose->update($data);
        return $glucose;
        // $glucose->update($data);
        // return $glucose;
    }

    public function delete(string | int $id): ?bool
    {
        if (!$glucose = $this->show($id))  return null;
        return $glucose->delete();
    }
}
