<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;

use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }




    public function userRegister(RegisterRequest $request)
    {

        $response = $this->authService->userRegister($request->validated(), $request);

        if (!$response['success']) {
            return response()->json([
                'success' => false,
                'message' => $response['message']
            ], 422);
        }

        $user = $response['user'];

        if ($user->hasRole('patient')) {
            return response()->json([
                'success' => true,
                'message' => $response['message'],
                'user' => new AuthResource($user->load('patient')),
                'token' => $response['token']
            ], 201);
        }

        // إذا كان الدور doctor
        return response()->json([
            'success' => true,
            'message' => $response['message'],
            'user' => new AuthResource($user->load('doctor', 'doctor.doctorCredentials', 'doctor.specializations'))
        ], 201);
    }

    public function userLogin(LoginRequest $request)
    {
        $result = $this->authService->userLogin($request->validated());

        // التحقق مما إذا كان هناك خطأ
        if (isset($result['error']) && $result['error']) {
            return response()->json([
                'message' => $result['message'],
            ], 401);
        }

        // إذا نجحت العملية
        $user = $result['user'];
        return response()->json([
            'user' => new AuthResource($user->load('doctor', 'doctor.doctorCredentials', 'patient')),
            'token' => $user->createToken('auth_token')->plainTextToken,
        ], 200);
    }




    public function handleGoogleCallback(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'token' => 'required|string',
                'fcm_token' => 'nullable|string'
            ]);

            $authData = $this->authService->handleGoogleCallback($validatedData);

            return response()->json([
                'message' => 'تم تسجيل الدخول بنجاح',
                'user' => new UserResource($authData['user']),
                'token' => $authData['token'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل تسجيل الدخول بجوجل',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function verifyUserCode(VerifyEmailRequest $request)
    {
        $verification = $this->authService->verifyUserCode($request->validated());

        if ($verification['success']) {
            return response()->json(['message' => $verification['message']], 200);
        }

        return response()->json(['message' => $verification['message']], 400);
    }


    public function resendVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $result = $this->authService->resendVerificationCode($request->email);

        return response()->json(['message' => $result['message']], $result['success'] ? 200 : 400);
    }
}
