<?php

namespace App\Services;

use App\Mail\PasswordResetMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetTokenService
{
    public function __construct() {}
    public function sendResetToken(array $data)
    {
        try {
            $user = User::where('email',$data['email'])->first();

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Email address not found',
                ];
            }

            
            if (!$user->password) {
                return [
                    'success' => false,
                    'message' => 'You are using Google sign-in. Password reset is not available for this account',
                ];
            }
            PasswordResetToken::where('email', $data['email'])->delete();

            $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            PasswordResetToken::create([
                'email'               => $data['email'],
                'verification_code'   => Hash::make($verificationCode),
                
            ]);

            Mail::to($user->email)->send(new PasswordResetMail($user, $verificationCode));
            return [
                'success' => true,
                'message' => 'Verification code has been sent to your email',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }



    public function verifyResetToken(array $data )
    {
        try {
            $resetRecord = PasswordResetToken::where('email', $data['email'])->first();

            if (!$resetRecord) {
                return [
                    'success' => false,
                    'message' => 'No verification code sent for this email address',
                ];
            }

            // Verify the code
            if (!Hash::check($data['verification_code'], $resetRecord->verification_code)) {
                return [
                    'success' => false,
                    'message' => 'Invalid verification code',
                ];
            }

            // Check if code has expired (15 minutes)
            if ($resetRecord->created_at->addMinutes(15)->isPast()) {
                $resetRecord->delete();

                return [
                    'success' => false,
                    'message' => 'Verification code has expired. Please request a new one',
                ];
            }

            return [
                'success' => true,
                'message' => 'Verification code is valid. You can now enter a new password',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }


}



