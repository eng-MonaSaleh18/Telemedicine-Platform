<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'age',
        'address',
        'phone',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function doctorCredentials()
    {
        return $this->hasMany(DoctorCredential::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }


    public function specializations()
    {
        return $this->belongsToMany(Specialization::class);
    }

    public function favoriteByPatient()
    {
        return $this->belongsToMany(Patient::class , 'patient_doctor_favorites' , 'patient_id' , 'doctor_id');
    }

    public function chatByDoctor()
    {
        return $this->hasMany(Chat::class);
    }


}
