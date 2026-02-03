<?php

namespace App\Services;

use App\Http\Requests\FcmTokenRequest;
use App\Mail\VerificationCodeMail;
use App\Models\Doctor;
use App\Models\DoctorCredential;
use App\Models\FcmToken;
use App\Models\Patient;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    public function __construct() {}

    public function fcmToken($fcmTokenValue, $userId = null)
    {
        $userId = $userId ?? Auth::id();  // استخدم Auth إذا كان متاحًا، وإلا userId المُمرر

        if (!$userId) {
            // اختياري: رمِ خطأً أو تجاهل إذا لم يكن user_id متاحًا
            return null;
        }

        $fcm_token = FcmToken::updateOrCreate(
            ['user_id' => $userId],  // البحث/التحديث بناءً على user_id فقط
            ['fcm_token' => $fcmTokenValue]  // حدث هذا الحقل دائمًا
        );

        return $fcm_token;
    }

    public function userRegister(array $data, Request $request = null)
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $user->assignRole($data['role']);

        if ($data['role'] == 'patient') {
            $patient = Patient::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'age' => $data['age'],
                'user_id' => $user->id
            ]);

            // إرسال رمز التحقق
            $this->sendVerificationCode($user);

            // إنشاء توكن للمريض وتسجيل الدخول
            $token = $user->createToken('auth_token')->plainTextToken;


            if (isset($data['fcm_token']) && !empty($data['fcm_token'])) {
                $this->fcmToken($data['fcm_token'], $user->id);  // الآن سيحدث السجل الموجود
            }


            return [
                'success' => true,
                'message' => 'The patient has been successfully registered and logged in.',
                'user' => $user,
                'token' => $token
            ];
        } else {
            $doctor = Doctor::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'age' => $data['age'],
                'user_id' => $user->id,
                'is_active' => false,
            ]);

            foreach ($data['doctorCredential'] as $credential) {
                $filePath = $credential['file_path']->store('credentials', 'public');
                DoctorCredential::create([
                    'doctor_id' => $doctor->id,
                    'file_path' => $filePath,
                    'file_name' => $credential['file_name'],
                    'description' => $credential['description'],
                ]);
            }
            $doctor->specializations()->sync($data['specialization_id']);

            // إرسال رمز التحقق
            $this->sendVerificationCode($user);

            if (isset($data['fcm_token']) && !empty($data['fcm_token'])) {
                $this->fcmToken($data['fcm_token'], $user->id);  // الآن سيحدث السجل الموجود
            }
            return [
                'success' => true,
                'message' => "Your doctor has been successfully registered. Please wait for the admin to activate your account.",
                'user' => $user
            ];
        }
    }


    public function userLogin(array $data, Request $request = null)
    {
        // البحث عن المستخدم باستخدام البريد الإلكتروني
        $user = User::where('email', $data['email'])->first();

        // إذا لم يتم العثور على المستخدم
        if (!$user) {
            return [
                'error' => true,
                'message' => 'The email is incorrect or not registered.'
            ];
        }

        // التحقق من كلمة المرور
        if (!Hash::check($data['password'], $user->password)) {
            return [
                'error' => true,
                'message' => 'The password is incorrect.'
            ];
        }

        if ($user->hasRole('doctor')) {
            $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor->is_active) {
                return [
                    'error' => true,
                    'message' => 'Your account is not activated. Please wait for an admin to activate your account.'
                ];
            }
        }

        if (isset($data['fcm_token']) && !empty($data['fcm_token'])) {
            $this->fcmToken($data['fcm_token'], $user->id);  // الآن سيحدث السجل الموجود
        }
        // إذا نجحت العملية، إرجاع المستخدم
        return [
            'error' => false,
            'user' => $user
        ];
    }



    public function handleGoogleCallback(array $data, Request $request = null)
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($data['token']);

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::updateOrCreate(
                    ['email' => $googleUser->email],
                    [
                        'name' => $googleUser->name,
                        'google_id' => $googleUser->id,
                    ]
                );
            }


            $token = $user->createToken('auth_token')->plainTextToken;


            if (isset($data['fcm_token']) && !empty($data['fcm_token'])) {
                $this->fcmToken($data['fcm_token'], $user->id);  // الآن سيحدث السجل الموجود
            }
            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (\Exception $e) {
            Log::error('Google Login Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw new \Exception('فشل تسجيل الدخول بجوجل: ' . $e->getMessage());
        }
    }




    public function generateVerificationCode()
    {
        $letters = range('A', 'Z'); // قائمة الحروف من A إلى Z
        do {
            // اختيار حرفين عشوائيين
            $letter1 = $letters[array_rand($letters)];
            $letter2 = $letters[array_rand($letters)];
            // التحقق من أن الحرفين غير متتاليين (فرق أكثر من 1 في الترتيب)
        } while (abs(ord($letter1) - ord($letter2)) <= 1);

        // توليد 4 أرقام عشوائية
        $numbers = str_pad(rand(0, 9999), 4,    '0', STR_PAD_LEFT);

        // دمج الحرفين والأرقام مع خلط عشوائي
        $code = $letter1 . $letter2 . $numbers;
        $code = str_shuffle($code); // خلط الترتيب لزيادة الأمان

        return $code;
    }


    public function sendVerificationCode(User $user)
    {
        // توليد رمز مخصص (حرفين غير متتاليين + 4 أرقام)
        $code = $this->generateVerificationCode();

        // إنشاء أو تحديث سجل التحقق
        VerificationCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        // إرسال الرمز عبر البريد الإلكتروني
        Mail::to($user->email)->send(new VerificationCodeMail($code, $user));
    }


    public function verifyUserCode(array $data)
    {
        $verification = VerificationCode::where('user_id', $data['user_id'])
            ->where('code', $data['code'])
            ->where('email_verified', false)
            ->first();

        if (!$verification) {
            return ['success' => false, 'message' => 'Invalid verification code or already used.'];
        }

        if (Carbon::now()->greaterThan($verification->expires_at)) {
            return ['success' => false, 'message' => 'Verification code has expired.'];
        }

        $verification->email_verified = true;
        $verification->save();

        return ['success' => true, 'message' => 'Email verified successfully.'];
    }


    public function resendVerificationCode($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        $newCode = $this->generateVerificationCode();

        VerificationCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'code' => $newCode,
                'expires_at' => Carbon::now()->addMinutes(5),
                'email_verified' => false,
            ]
        );

        Mail::to($user->email)->send(new VerificationCodeMail($newCode, $user));

        return ['success' => true, 'message' => 'New verification code sent to your email.'];
    }
}
