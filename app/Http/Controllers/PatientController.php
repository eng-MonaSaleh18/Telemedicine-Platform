<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteDoctorRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\PatientResource;
use App\Http\Resources\SpecializationResource;
use App\Models\Patient;
use App\Models\Specialization;
use App\Services\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    
    protected $patientService;
    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }


    



    public function patientEditInfo(PatientRequest $request,  $patient_id)
    {
        $patient = $this->patientService->patientEditInfo($request->validated(), $patient_id);
        return response()->json([
            'patient' => new PatientResource($patient->load('user')),
        ]);
    }



    public function getAllSpecializations()
    {
        $specializations = $this->patientService->getAllSpecializations();
        return response()->json(SpecializationResource::collection($specializations->load('doctors')));
    }



    public function searchDoctorByName(Request $request)
    {
        $doctors = $this->patientService->searchDoctorByName($request);
        return response()->json(new PaginatedCollection(DoctorResource::collection($doctors)));
    }


    public function filterDoctor(Request $request)
    {
        $doctors = $this->patientService->filterDoctor($request);
        return response()->json(new PaginatedCollection(DoctorResource::collection($doctors)));
    }
    


    public function addToFavorite(FavoriteDoctorRequest $request)
    {
        
        $favoriteDoctors = $this->patientService->addToFavorite($request->validated());
        return response()->json([
            'message' => 'Your doctor has been added to favorites '
        ]);
    }

    public function removeFromFavorite(FavoriteDoctorRequest $request)
    {
        $favoriteDoctors = $this->patientService->removeFromFavorite($request->validated());
        return response()->json([
            'message' => 'Your doctor has been removed from favorites '
        ]);
    }

    public function getAllFavoriteDoctor()
    {
        $favoriteDoctors = $this->patientService->getAllFavoriteDoctor();
        return response()->json(new PaginatedCollection(DoctorResource::collection($favoriteDoctors)));
    }



    public function getAllDoctor()
    {
        $doctors = $this->patientService->getAllDoctor();
        return response()->json(new PaginatedCollection(DoctorResource::collection($doctors)));
    }
}
