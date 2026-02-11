<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;

class LeadController extends Controller
{
    /**
     * POST /api/leads
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        // Создаем лид    
        $lead = Lead::create($request->validated());
        
        return response()->json([
            'success' => true,
            'lead_id' => $lead->id,
            'message' => 'Lead created successfully'
        ], 201);
    }
}
