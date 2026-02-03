<?php

namespace App\Services;

use App\Mail\ConsultationCreatedMail;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDO;

class ConsultationService
{
    public function __construct(){}

    public function createConsultation(array $data)
    {
        $appointment = Appointment::findOrFail($data['appointment_id']);
        $doctor = Doctor::where('user_id' , Auth::id())->first();
        if($appointment->doctor_id != $doctor->id){
            throw new Exception("Dear Dr. {$doctor->first_name} {$doctor->last_name}, it seems this appointment is assigned to another doctor. Please check your schedule for your own appointments.");
        }

        $consultation = Consultation::create([
            'appointment_id' => $data['appointment_id'],
            'doctor_id' => $doctor->id,
            'patient_id' => $appointment->patient_id,
            'notes' => $data['notes'],
            'meet_url' => $data['meet_url']
        ]);

        $patient = $appointment->patient;
        if ($patient && $patient->user && $patient->user->email) {
            Mail::to($patient->user->email)->send(new ConsultationCreatedMail($consultation, $doctor , $patient));
        } else {
            throw new Exception("لا يمكن إرسال البريد الإلكتروني: بريد المريض غير موجود.");
        }
        return $consultation ;
    }

    
}