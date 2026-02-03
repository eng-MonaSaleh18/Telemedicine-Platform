<?php

namespace App\Services;

use App\Models\Specialization;

class SpecializationService
{
    public function __construct(){}

    public function getAllSpecialization()
    {
        $specialization = Specialization::all();
        return $specialization ;
    }

    public function storeSpecialization(array $data)
    {
        $specialization = Specialization::create([
            'Specialization' => $data['Specialization']
        ]);
        return $specialization ;
    }

    public function editSpecialization(array $data , $specialization_id)
    {
        $specialization = Specialization::find($specialization_id);
        $specialization->update([
            'Specialization' => $data['Specialization']
        ]);
        return $specialization ;
    }
    public function showSpecialization($specialization_id)
    {
        $specialization = Specialization::find($specialization_id);
        return $specialization ;
    }
}