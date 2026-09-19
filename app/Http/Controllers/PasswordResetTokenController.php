<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\VerifyResetCodeRequest;
use App\Models\PasswordResetToken;
use App\Services\PasswordResetTokenService;
use Illuminate\Http\Request;

class PasswordResetTokenController extends Controller
{
    private $passwordResetTokenService;

    public function __construct(PasswordResetTokenService $passwordResetTokenService)

    {
        $this->passwordResetTokenService = $passwordResetTokenService;
    }
    



    public function sendResetToken(ForgotPasswordRequest $request)
    {
        $result = $this->passwordResetTokenService->sendResetToken($request->validated());
        return response()->json([
            'success' => true,
            'message' => $result['message']
        ], 200);
    }



    public function verifyResetCode(VerifyResetCodeRequest $request)
    {
        $result = $this->passwordResetTokenService->verifyResetToken($request->validated());

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message']
        ], 200);
    }

}
