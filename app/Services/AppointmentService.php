<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Chat;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class AppointmentService
{
    public function __construct() {}

    public function createAppointment(array $data)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        $appointmentConflict = Appointment::where('doctor_id', $doctor->id)
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('start_time', '<=', $data['start_time'])
                            ->where('end_time', '>=', $data['end_time']);
                    });
            })->exists();

        if ($appointmentConflict) {
            throw new Exception('Time slot conflict', 422);
        }

        $appointment = Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => null,
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'price' => $data['price'],
            'notes' => data_get($data, 'notes', null),
            'status' => 'pending'
        ]);
        return $appointment;
    }

    public function bookAnAppoientment(array $data)
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        $appointment = Appointment::where('id', $data['appointment_id'])->first();

        $patientFirstName = Auth::user()->patient->first_name;
        $patientLastName = Auth::user()->patient->last_name;

        if ($appointment->status != 'pending' && $appointment->status != 'cancelled') {
            throw new Exception('Sorry dear ' . $patientFirstName . ' ' . $patientLastName . ', this appointment has already been booked by another patient.');
        }
        $patientAppoientments = Appointment::where('patient_id', $patient->id)->get();
        foreach ($patientAppoientments as $patientAppoientment) {
            if ($patientAppoientment->start_time == $appointment->start_time) {
                throw new Exception('Sorry dear ' . $patientFirstName . ' ' . $patientLastName . ', you have another appointment at the same time. You cannot book two appointments at the same time.');
            }
        }

        return $appointment->update([
            'status' => 'confirmed',
            'patient_id' => $patient->id
        ]);
    }

    public function cancelAppointment(array $data)
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        $appointment = Appointment::where('id', $data['appointment_id'])
            ->where('patient_id', $patient->id)->first();
        if ($appointment->status == 'cancelled' ||  $appointment->status == 'completed') {
            throw new Exception('This appointment is either cancelled or completed and cannot be modified.');
        }

        return $appointment->update([
            'status' => 'cancelled',
            'patient_id' => null
        ]);
    }


    public function completeAppointment(array $data)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        $appointment = Appointment::where('id', $data['appointment_id'])->first();
        if (!$doctor) {
            throw new Exception('Only doctors can mark appointments as completed.', 403);
        }

        // التحقق من حالة الموعد
        if ($appointment->status === 'completed') {
            throw new Exception("Sorry dear Dr. {$doctor->first_name} {$doctor->last_name}, this appointment is already completed.", 422);
        }

        if ($appointment->status === 'cancelled' || $appointment->status === 'pending') {
            throw new Exception("Sorry dear Dr. {$doctor->first_name} {$doctor->last_name}, this appointment is cancelled or pending and cannot be marked as completed.", 422);
        }

        // التحقق مما إذا كان الموعد لم يبدأ بعد
        if (Carbon::parse($appointment->start_time)->gte(Carbon::now())) {
            throw new Exception("Sorry dear Dr. {$doctor->first_name} {$doctor->last_name}, this appointment has not started yet. You cannot confirm its completion.", 422);
        }

        // تحديث حالة الموعد إلى completed
        $appointment->update([
            'status' => 'completed',
        ]);
        $existingChat = Chat::where('doctor_id', $appointment->doctor_id)
            ->where('patient_id', $appointment->patient_id)
            ->first();
            
        if (!$existingChat) {
            Chat::create([
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
            ]);
        }
        return $appointment;
    }


    public function getAvailableAppointments()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor->is_active) {
            throw new Exception("Dear Dr. {$doctor->first_name} {$doctor->last_name}, your account is deactivated. Please wait for an admin to activate your account.");
        }
        $appointment = Appointment::whereIn('status', ['pending', 'cancelled'])
            ->where('doctor_id', $doctor->id)->get();

        return $appointment;
    }

    public function getAllAppointments()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        $appointment = Appointment::where('doctor_id', $doctor->id)->get();

        return $appointment;
    }


    /* get all appointments booked by the patient  */
    public function getMyAppoientments()
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        $appointment = Appointment::where('patient_id', $patient->id)->get();

        return $appointment;
    }


    public function cancelAppointmentByDoctor(array $data)
    {
        $appointment = Appointment::where('id', $data['appointment_id'])->first();
        if ($appointment->status == 'cancelled' ||  $appointment->status == 'completed') {
            throw new Exception('This appointment is either cancelled or completed and cannot be modified.');
        }

        return $appointment->update([
            'status' => 'cancelled',
            'patient_id' => null
        ]);
    }
}
