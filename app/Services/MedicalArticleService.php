<?php

namespace App\Services;

use App\Models\MedicalArticle;

class MedicalArticleService
{
    public function __construct(){}

    public function getAllMedicalArticle()
    {
        $medicalArticle = MedicalArticle::get();
        return $medicalArticle;
    }


    public function storeMedicalArticle(array $data)
    {
        $medicalArticle = MedicalArticle::create([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
        return $medicalArticle ;
    }

    
public function updateMedicalArticle(MedicalArticle $medicalArticle , array $data)
    {
        $medicalArticle->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
        return $medicalArticle;
    }

    public function deleteMedicalArticle(MedicalArticle $medicalArticle )
    {
        $medicalArticle->delete();
        return $medicalArticle;
    }
}