<?php
namespace App\Services;

use App\Mail\DoctorActivatedMail;
use App\Mail\DoctorDeactivatedMail;
use App\Models\Doctor;
use App\Models\DoctorCredential;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Kreait\Firebase\Auth;

class DoctorService
{
    public function __construct()
    {}

    public function doctorEditInfo(array $data)
    {
        try {
            DB::beginTransaction();

            $doctor = auth()->user()->doctor;
            $doctor = Doctor::findOrFail($doctor->id);

            // ✅ تحديث بيانات الطبيب
            $doctor->update([
                'first_name'          => $data['first_name'],
                'last_name'           => $data['last_name'],
                'address'             => $data['address'],
                'phone'               => $data['phone'],
                'age'                 => $data['age'],
                'specialization_id'   => $data['specialization_id'],
                'languages'           => $data['languages'],
                'years_of_experience' => $data['years_of_experience'],
            ]);

            // ✅ تحديث credentials فقط إذا تم إرسالها
            if (isset($data['doctorCredential']) && ! empty($data['doctorCredential'])) {
                // حذف القديم
                $doctor->doctorCredentials()->delete();

                // إضافة الجديد
                foreach ($data['doctorCredential'] as $credential) {
                    $filePath = $credential['file_path']->store('credentials', 'public');
                    DoctorCredential::create([
                        'doctor_id'   => $doctor->id,
                        'file_path'   => $filePath,
                        'file_name'   => $credential['file_name'],
                        'description' => $credential['description'],
                    ]);
                }
            }

            DB::commit();

            return $doctor->load('doctorCredentials');

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to update doctor information: ' . $e->getMessage());
        }
    }

    public function activateDoctor($doctor_id)
    {
        $doctor = Doctor::findOrFail($doctor_id);
        if ($doctor->is_active) {
            throw new Exception("The account of Dr. {$doctor->first_name} {$doctor->last_name} is already activated.");
        }

        $doctor->update(['is_active' => true]);

        Mail::to($doctor->user->email)->send(new DoctorActivatedMail($doctor));

        return $doctor;
    }

    public function deactivateDoctor($doctor_id)
    {
        $doctor = Doctor::findOrFail($doctor_id);
        if (! $doctor->is_active) {
            throw new Exception("The account of Dr. {$doctor->first_name} {$doctor->last_name} is already deactivated.");
        }

        $doctor->update(['is_active' => false]);
        Mail::to($doctor->user->email)->send(new DoctorDeactivatedMail($doctor));
        return $doctor;
    }
}
