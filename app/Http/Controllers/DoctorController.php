<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PaginatedCollection;
use App\Models\Doctor;
use App\Services\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    private $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }
    /**
     * Display a listing of the resource.
     */
    

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Update the specified resource in storage.
     */
    public function doctorEditInfo(DoctorRequest $request, $doctor_id)
    {
        
        try {
            $doctor = $this->doctorService->doctorEditInfo($request->validated(), $doctor_id);
            return response()->json([
                'doctor' => new DoctorResource($doctor->load('user'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function activateDoctor($doctor_id)
    {
        try {
            $doctor = $this->doctorService->activateDoctor( $doctor_id);
            return response()->json([
                'message' => 'the doctor account is activated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function deactivateDoctor($doctor_id)
    {
        try {
            $doctor = $this->doctorService->deactivateDoctor( $doctor_id);
            return response()->json([
                'message' => 'the doctor account is deactivated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' =>  $e->getMessage()
            ], 400);
        }
    }
}
