<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalArticleRequest;
use App\Http\Resources\MedicalArticleResource;
use App\Models\MedicalArticle;
use App\Services\MedicalArticleService;
use Illuminate\Http\Request;

class MedicalArticleController extends Controller
{
    private $medicalArticleService;

    public function __construct(MedicalArticleService $medicalArticleService)
    {
        $this->medicalArticleService = $medicalArticleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function getAllMedicalArticle()
    {
        $medicalArticle = $this->medicalArticleService->getAllMedicalArticle();
        return response()->json(MedicalArticleResource::collection($medicalArticle));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function storeMedicalArticle(MedicalArticleRequest $request)
    {
        $medicalArticle = $this->medicalArticleService->storeMedicalArticle($request->validated());
        return response()->json([
            'message' => "Stored Successfully!",
            'data'    => new MedicalArticleResource($medicalArticle),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function updateMedicalArticle(MedicalArticleRequest $request , MedicalArticle $medicalArticle)
    {
        $medicalArticle = $this->medicalArticleService->updateMedicalArticle($medicalArticle , $request->validated()  );
        return response()->json([
            'message' => "Upadeted Successfully!",
            'data'    => new MedicalArticleResource($medicalArticle),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteMedicalArticle(MedicalArticle $medicalArticle)
    {
        $medicalArticle = $this->medicalArticleService->deleteMedicalArticle($medicalArticle);
        return response()->json([
            'message' => "Deleted Successfully!",
            
        ]);
    }
}
