<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConsultationRequest;
use App\Http\Resources\ConsultationResource;
use App\Models\Consultation;
use App\Services\ConsultationService;
use Exception;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    private $consultationService ;
    public function __construct(ConsultationService $consultationService)
    {
        $this->consultationService = $consultationService ;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createConsultation(ConsultationRequest $request)
    {
        
        try {
            $consultation = $this->consultationService->createConsultation($request->validated());
        return response()->json(new ConsultationResource($consultation));
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consultation $consultation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consultation $consultation)
    {
        //
    }
}
