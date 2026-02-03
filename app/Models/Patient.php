<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'age',
        'address',
        'phone',
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


    public function medicaleFiles()
    {
        return $this->hasMany(MedicalFile::class);
    }

    public function medicaleHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }




    public function favoriteDoctors()
    {
        return $this->belongsToMany(Doctor::class, 'patient_doctor_favorites', 'patient_id', 'doctor_id');
    }

    public function chatByPatient()
    {
        return $this->hasMany(Chat::class);
    }
}
