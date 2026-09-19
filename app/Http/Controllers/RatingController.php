<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use App\Services\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    private $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    
    /**
     * Store a newly created resource in storage.
     */
    public function createRating(StoreRatingRequest $request)
    {
        try {
            $rating = $this->ratingService->createRating($request->validated());

            return response()->json([
                'message' => 'Rating created successfully',
                'data' => new RatingResource($rating)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        //
    }
}
