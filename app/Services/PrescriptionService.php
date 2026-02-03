<?php

namespace App\Services;

use App\Mail\PrescriptionCreatedMail;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\MedicalFile;
use App\Models\MedicalHistory;
use App\Models\Prescription;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PrescriptionService
{
    public function __construct() {}

    public function addAMedicalHistory(array $data ,  Consultation $consultation)
    {
        
        $medicalHistory = MedicalHistory::create([
            'patient_id' => $consultation->patient_id,
            'chronic_diseases' => $data['chronic_diseases'],
            'allergies' =>  $data['allergies'],
            'current_medications' => $data['current_medications'],
        ]);
        return $medicalHistory ;
    }


    public function storeMultipleFiles(array $files, string $directory = 'medical_files'): array
    {
        $storedFiles = [];

        foreach ($files as $file) {
                // Store the file
                $filePath = $file->store($directory, 'public');
                $fileName = $file->getClientOriginalName();

                $storedFiles[] = [
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                ];
            
        }

        return $storedFiles;
    }


    public function addAMedicalFile(array $data, array $files , Consultation $consultation , Prescription $prescription)
    {
        
        if (empty($files) || empty($data['medical_files'])) {
            throw new Exception('يجب توفير ملفات طبية وبياناتها.');
        }

        $storedFiles = $this->storeMultipleFiles($files);
        $medicalFiles = [];

        foreach ($storedFiles as $index => $fileData) {
            $medicalFileInfo = $data['medical_files'][$index] ?? null;
            if (!$medicalFileInfo || !isset($medicalFileInfo['file_name'], $medicalFileInfo['description'])) {
                throw new Exception("بيانات الملف الطبي رقم {$index} غير مكتملة.");
            }

            $medicalFiles[] = MedicalFile::create([
                'prescription_id' => $prescription->id,
                'patient_id' => $consultation->patient_id,
                'file_path' => $fileData['file_path'],
                'file_name' => $medicalFileInfo['file_name'], // من الطلب
                'description' => $medicalFileInfo['description'], // من الطلب
            ]);
        }

        return $medicalFiles;
    }

    public function createAPrescription(array $data ,  Consultation $consultation , Doctor $doctor)
    {
        $prescription = Prescription::create([
            'consultation_id' => $data['consultation_id'],
            'patient_id' => $consultation->patient_id,
            'doctor_id' => $doctor->id,
            'medication' => $data['medication'],
            'instructions' => $data['instructions'],
            'signature' => $data['signature'],
        ]);
        return $prescription;
    }

    public function addAPrescription(array $data , array $files = [])
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        $consultation = Consultation::find($data['consultation_id']);
        if (!$consultation) {
            throw new Exception("Sorry dear Dr. {$doctor->first_name} {$doctor->last_name}, you must add a consultation first.");
        }
        
        $prescription = $this->createAPrescription($data , $consultation , $doctor);
        $medicalFile = $this->addAMedicalFile($data ,$files , $consultation , $prescription);
        $medicalHistory = $this->addAMedicalHistory($data , $consultation);

        $patient = $prescription->patient;
        if ($patient && $patient->user && $patient->user->email) {
            Mail::to($patient->user->email)->send(new PrescriptionCreatedMail( $doctor , $patient));
        } else {
            throw new Exception("لا يمكن إرسال البريد الإلكتروني: بريد المريض غير موجود.");
        }
        return [
            'prescription' => $prescription,
            'medical_history' => $medicalHistory,
            'medical_files' => $medicalFile,
        ];
    }
}
