<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;

class PatientService
{
    public function __construct() {}

    public function patientEditInfo(array $data, $patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $patient->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'age' => $data['age'],
        ]);
        return $patient;
    }

    public function getAllSpecializations()
    {
        $specializations = Specialization::with('doctors')->get();
        return $specializations;
    }


    public function searchDoctorByName($request)
    {

        $firstName = $request['first_name'];
        $lastName = $request['last_name'];

        $query = Doctor::query();
        if ($firstName) {
            $query->where('first_name', 'like', "%{$firstName}%");
        }
        if ($lastName) {
            $query->where('last_name', 'like', "%{$lastName}%");
        }

        return $doctors = $query
            ->with(['user', 'doctorCredentials', 'specializations'])->paginate(10);
    }



    public function filterDoctor($request)
    {

        $specializationId = $request->input('specialization_id');
        $age = $request->input('age');
        $address = $request->input('address');

        $query = Doctor::query()->with(['user', 'doctorCredentials', 'specializations']);

        if ($specializationId) {
            $query->whereHas('specializations', function ($q) use ($specializationId) {
                $q->where('specializations.id', '=', $specializationId); // تحديد الجدول صراحة
            });
        }
        if ($age) {
            $query->where('age', '=', $age);
        }
        if ($address) {
            $query->where('address', 'like', "%{$address}%");
        }

        return $query->paginate(10);
    }


    public function addToFavorite(array $data)
    {
        $patient = auth()->user()->patient;

        $doctor = Doctor::find($data['doctor_id']);

        if($patient->favoriteDoctors()->where('doctor_id' , $doctor->id)->exists())
        {
            return response()->json([
                'message' => 'Doctor is already in favorites.'
            ]);
        }

        return $patient->favoriteDoctors()->attach($doctor->id);

    }

    public function removeFromFavorite(array $data)
    {
        $patient = auth()->user()->patient;

        $doctor = Doctor::find($data['doctor_id']);

        if($patient->favoriteDoctors()->where('doctor_id' , $doctor->id)->exists())
        {
            return $patient->favoriteDoctors()->detach($doctor->id);
        }

    }

    public function getAllFavoriteDoctor()
    {
        $patient = auth()->user()->patient ;

        $favoriteDoctors = $patient->favoriteDoctors()->with('user' , 'doctorCredentials' , 'specializations')->paginate(10);

        return $favoriteDoctors ;

    }


    public function getAllDoctor()
    {
        $doctors = Doctor::with('user', 'specializations')
            ->withAvg('ratings', 'rating')  // ✅ حساب المتوسط
            ->withCount('ratings')           // ✅ عدد التقييمات
            ->paginate(10);
        return $doctors;
    }
}
