<?php
namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\FAQ;
use App\Services\FaqService;

class FAQController extends Controller
{
    private $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }
    /**
     * Display a listing of the resource.
     */
    public function getAllFaq()
    {
        $faq = $this->faqService->getAllFaq();
        return response()->json(FaqResource::collection($faq));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeFaq(FaqRequest $request)
    {
        $faq = $this->faqService->storeFaq($request->validated());
        return response()->json([
            'message' => "Stored Successfully!",
            'data'    => new FaqResource($faq),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(FAQ $fAQ)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateFaq(FaqRequest $request, FAQ $faq)
    {
        $faq = $this->faqService->updateFaq($faq, $request->validated());
        return response()->json([
            'message' => "Updated Successfully!",
            'data'    => new FaqResource($faq),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteFaq(FAQ $faq)
    {
        $faq = $this->faqService->deleteFaq($faq);
        return response()->json([
            'message' => "Deleted Successfully!",
            
        ]);
    }
}
