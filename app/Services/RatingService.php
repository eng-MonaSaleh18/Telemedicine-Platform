<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Rating;
use App\Models\User;
use Kreait\Firebase\Auth;

class RatingService
{
    public function __construct(){}

    public function canRateDoctor($patientId, $doctorId)
    {
        // التحقق من وجود موعد مكتمل
        $completedAppointment = Appointment::where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->exists();

        return $completedAppointment;
    }

    public function createRating(array $data)
    {
        $doctorId = $data['doctor_id'];
        $patientId = auth()->user()->patient->id;
        
        if (!$this->canRateDoctor($patientId, $doctorId)) {
            throw new \Exception('You can only rate a doctor after completing an appointment.');
        }

        $rating = Rating::updateOrCreate(
            [
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
            ],
            [
                'rating' => $data['rating'],
            ]
        );
        return  $rating ;
    }


}