<?php
namespace App\Http\Controllers;

use App\Http\Requests\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\PaginatedCollection;
use App\Models\Complaint;
use App\Services\ComplaintService;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{

    private $complaintService;

    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
    }



    public function getComplaintsByComplainant($Complainant_id)
    {
        try {
            $complaints = $this->complaintService->getComplaintsByComplainant($Complainant_id);
            
            return response()->json([
                'message' => 'Successfully!',
                'complaint' => new PaginatedCollection(ComplaintResource::collection($complaints))
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get complaints',
                'error' => $e->getMessage()
            ], 400);
        }
    }



    public function getComplaintsByAccused($accused_id)
    {
        try {
            $complaints = $this->complaintService->getComplaintsByAccused($accused_id);
            
            return response()->json([
                'message' => 'Successfully!',
                'complaint' => new PaginatedCollection(ComplaintResource::collection($complaints))
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get complaints',
                'error' => $e->getMessage()
            ], 400);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function createComplaint(ComplaintRequest $request)
    {
        try {
            $complaint = $this->complaintService->createComplaint($request->validated());
            
            return response()->json([
                'message' => 'Created Successfully!',
                'complaint' => new ComplaintResource($complaint)
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create complaint',
                'error' => $e->getMessage()
            ], 400);
        }
    }




    public function myComplaints()
    {
        try {
            $complaint = $this->complaintService->myComplaints();
            
            return response()->json([
                'message' => 'Successfully!',
                'complaint' => new PaginatedCollection(ComplaintResource::collection($complaint))
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get complaint',
                'error' => $e->getMessage()
            ], 400);
        }
    }



    public function complaintsAgainstMe()
    {
        try {
            $complaint = $this->complaintService->complaintsAgainstMe();
            
            return response()->json([
                'message' => 'Successfully!',
                'complaint' => new PaginatedCollection(ComplaintResource::collection($complaint))
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get complaint',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    


    public function deleteComplaint(Complaint $complaint)
    {
        try {
            $complaint = $this->complaintService->deleteComplaint($complaint);
            
            return response()->json([
                'message' => 'Deleted Successfully!',
                
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to get complaint',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
