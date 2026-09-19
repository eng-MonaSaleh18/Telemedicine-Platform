<?php
namespace App\Services;

use App\Models\Complaint;

class ComplaintService
{
    public function __construct()
    {}

    public function createComplaint(array $data)
    {
        $patient_id = auth()->user()->patient->id;
        $complaint  = Complaint::create([
            'patient_id' => $patient_id ,
            'doctor_id' => $data['doctor_id'],
            'complaint_type' => $data['complaint_type'],
            'description' => $data['description'],
            'contact_number' => $data['contact_number'],
        ]);
        return $complaint ;
    }



    public function getComplaintsByComplainant($Complainant_id)
    {
        $complaints = Complaint::where('patient_id', $Complainant_id)
            ->with(['doctor', 'patient'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return $complaints;
    }

    public function getComplaintsByAccused($accused_id)
    {
        $complaints = Complaint::where('doctor_id', $accused_id)
            ->with(['doctor', 'patient'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return $complaints;
    }


    public function myComplaints()
    {
        $patient_id = auth()->user()->patient->id ;
        $complaints = Complaint::where('Patient_id', $patient_id)
            ->with(['doctor'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return $complaints;
    }


    public function complaintsAgainstMe()
    {
        $doctor_id = auth()->user()->doctor->id ;
        $complaints = Complaint::where('doctor_id', $doctor_id)
            ->with(['patient'])
            ->orderByDesc('created_at')
            ->paginate(10);
        return $complaints;
    }

    public function deleteComplaint(Complaint $complaint)
    {
        $complaint->delete();
        return $complaint;
    }




}
