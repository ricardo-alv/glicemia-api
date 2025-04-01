<?php

namespace App\Http\Controllers\Api\Dm1;

use App\Http\Requests\Api\GlucoseDay\StoreUpdate;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GlucoseDayResource;
use App\Http\Controllers\Controller;
use App\Services\GlucoseDayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlucoseDayController extends Controller
{
    public function __construct(
        protected  GlucoseDayService $glucoseDayService
    ) {}

    public function index(Request $request): JsonResource
    {
        $glucoses_days =  $this->glucoseDayService->getAll($request->all());
        return GlucoseDayResource::collection($glucoses_days);
    }

    public function store(StoreUpdate $request): JsonResource
    {
        $glucose_day =  $this->glucoseDayService->create($request->validated());
        return new GlucoseDayResource($glucose_day);
    }

    public function show(string | int $id): JsonResource | JsonResponse
    {
        if (!$glucose_day = $this->glucoseDayService->show($id)) {
            return response()->json(['msg' => 'Dia glicemia não encontrada!'], 404);
        }

        return new GlucoseDayResource($glucose_day);
    }

    public function update(StoreUpdate $request, string $id): JsonResponse | JsonResource
    {
        if (!$glucose_day = $this->glucoseDayService->update($request->validated(), $id)) {
            return response()->json(['msg' => 'Dia glicemia não encontrada!'], 404);
        }

        return new GlucoseDayResource($glucose_day);
    }

    public function destroy(string | int $id): JsonResponse
    {
        if (!$this->glucoseDayService->delete($id)) {
            return response()->json(['msg' => 'Dia glicemia não encontrada!'], 404);
        }

        return response()->json([], 204);
    }
}
