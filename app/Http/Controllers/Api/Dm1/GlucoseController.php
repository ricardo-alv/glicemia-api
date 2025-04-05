<?php

namespace App\Http\Controllers\Api\Dm1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Glucose\StoreUpdateGlucose;
use App\Http\Resources\GlucoseResource;
use App\Models\MealType;
use App\Services\GlucoseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GlucoseController extends Controller
{
    public function __construct(
        protected  GlucoseService $glucoseService
    ) {}

    public function index(Request $request): JsonResource
    {
        $glucoses =  $this->glucoseService->getAll($request->all());
        $meal_types = MealType::orderBy('created_at')->get();
        return GlucoseResource::collection($glucoses)->additional([
            'meal_types' => $meal_types
        ]);
    }

    public function store(StoreUpdateGlucose $request): JsonResource
    {
        $glucose =  $this->glucoseService->createUpdate($request->validated());
        return new GlucoseResource($glucose);
    }   

    public function destroy(string | int $id): JsonResponse
    {
        if (!$this->glucoseService->delete($id)) {
            return response()->json(['msg' => 'Glicemia não encontrada!'], 404);
        }

        return response()->json([], 204);
    }

    public function export(Request $request)
    {
        // return $this->glucoseService->exportGlucose($request->all());

        try {
            return $this->glucoseService->exportGlucose($request->all());
        } catch (\Throwable $e) {
            Log::error('Erro no controller export:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'message' => 'Erro inesperado ao exportar o relatório.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
