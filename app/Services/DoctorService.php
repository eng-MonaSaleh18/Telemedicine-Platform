<?php

namespace App\Services;

use App\Mail\DoctorActivatedMail;
use App\Mail\DoctorDeactivatedMail;
use App\Models\Doctor;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

class DoctorService
{
    public function __construct() {}

    
    public function doctorEditInfo(array $data, $doctor_id)
    {
        $doctor = Doctor::findOrFail($doctor_id);
        $doctor->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'age' => $data['age'],
        ]);
        return $doctor ;
    }

    public function activateDoctor($doctor_id)
    {
        $doctor = Doctor::findOrFail($doctor_id);
        if ($doctor->is_active) {
            throw new Exception("The account of Dr. {$doctor->first_name} {$doctor->last_name} is already activated.");
        }

        $doctor->update(['is_active' => true]);

        Mail::to($doctor->user->email)->send(new DoctorActivatedMail($doctor));
        
        return $doctor;
    }

    public function deactivateDoctor($doctor_id)
    {
        $doctor = Doctor::findOrFail($doctor_id);
        if (!$doctor->is_active) {
            throw new Exception("The account of Dr. {$doctor->first_name} {$doctor->last_name} is already deactivated.");
        }

        $doctor->update(['is_active' => false]);
        Mail::to($doctor->user->email)->send(new DoctorDeactivatedMail($doctor));
        return $doctor;
    }
}
