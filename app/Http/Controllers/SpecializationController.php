<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecializationRequest;
use App\Http\Resources\SpecializationResource;
use App\Models\Specialization;
use App\Services\SpecializationService;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    private $specializationService;

    public function __construct(SpecializationService $specializationService)
    {
        $this->specializationService = $specializationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function getAllSpecialization()
    {
        $specialization = $this->specializationService->getAllSpecialization();
        return response()->json(SpecializationResource::collection($specialization->load(['doctors' , 'doctors.user' , 'doctors.doctorCredentials'])));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSpecialization(SpecializationRequest $request)
    {
        $specialization = $this->specializationService->storeSpecialization($request->validated());
        return response()->json(new SpecializationResource($specialization));
    }

    
    
    /**
     * Update the specified resource in storage.
     */
    public function editSpecialization(SpecializationRequest $request, $specialization_id)
    {
        $specialization = $this->specializationService->editSpecialization($request->validated() , $specialization_id);
        return response()->json(new SpecializationResource($specialization));
    }


    public function showSpecialization($specialization_id)
    {
        $specialization = $this->specializationService->showSpecialization($specialization_id);
        return response()->json(new SpecializationResource($specialization->load(['doctors' , 'doctors.user' , 'doctors.doctorCredentials'])));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialization $specialization)
    {
        //
    }
}
