<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    public function __construct()
    {
        //
    }


    /* ----------جلب محادثات الطبيب مع المرضى لديه  ------- */
    public function getDoctorChats()
    {
        $doctor = Doctor::where('user_id' , Auth::id())->first();
        $chats = Chat::where('doctor_id' , $doctor->id)->with('patient' , 'patient.user' , 'messages')->get();
        return $chats ;
    }


    /* -----------جلب محادثات المريض مع الاطباء الخاصة به -------- */
    public function getPatientChats()
    {
        $patient = Patient::where('user_id' , Auth::id())->first();
        $chats = Chat::where('patient_id' , $patient->id)->with('doctor' , 'doctor.user' , 'messages')->get();
        return $chats ;
    }
}