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
        'is_active',
        'specialization_id',
        'languages',
        'years_of_experience',
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


    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function favoriteByPatient()
    {
        return $this->belongsToMany(Patient::class , 'patient_doctor_favorites' , 'patient_id' , 'doctor_id');
    }

    public function chatByDoctor()
    {
        return $this->hasMany(Chat::class);
    }


    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function complaintsAgainstMe()
    {
        return $this->hasMany(Complaint::class);
    }
}
