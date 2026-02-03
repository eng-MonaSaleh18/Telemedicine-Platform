<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorCredential extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'file_path',
        'file_name',
        'description',
        'Specialization',
    ];


    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    } 
}
