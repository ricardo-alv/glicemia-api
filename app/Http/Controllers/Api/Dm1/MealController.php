<?php

namespace App\Http\Controllers\Api\Dm1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealTypeResource;
use App\Models\MealType;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $meal_types = MealType::orderBy('created_at')->get();
        return MealTypeResource::collection($meal_types);
    }
}
