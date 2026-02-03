<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\BookAppoientmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Exception;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    private $appointmentService;
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function getAllAppointments()
    {
        $allAppointment = $this->appointmentService->getAllAppointments();
        return response()->json( AppointmentResource::collection($allAppointment), 201);
    }


    public function getAvailableAppointments()
    {
        $availableAppointment = $this->appointmentService->getAvailableAppointments();
        return response()->json( AppointmentResource::collection($availableAppointment), 201);
    }

    public function getMyAppoientments()
    {
        $myAppointment = $this->appointmentService->getMyAppoientments();
        return response()->json( AppointmentResource::collection($myAppointment), 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createAppointment(AppointmentRequest $request)
    {
        try {
            $appointment = $this->appointmentService->createAppointment($request->validated());
            return response()->json(new AppointmentResource($appointment), 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    public function bookAnAppoientment(BookAppoientmentRequest $request)
    {
        try {
            $appointment = $this->appointmentService->bookAnAppoientment($request->validated());
            return response()->json([
                'message' => 'The appoientment has been booked successfully!'
            ]);
        } catch (Exception $e) {
            return  response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    public function cancelAppointment(BookAppoientmentRequest $request)
    {
        try {
            $appointment = $this->appointmentService->cancelAppointment($request->validated());
            return response()->json([
                'message' => 'The appoientment has been cancelled successfully!'
            ]);
        } catch (Exception $e) {
            return  response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    public function completeAppointment(BookAppoientmentRequest $request)
    {
        try {
            $appointment = $this->appointmentService->completeAppointment($request->validated());
            return response()->json([
                'message' => 'The appoientment has been completed successfully!'
            ]);
        } catch (Exception $e) {
            return  response()->json([
                'message' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
