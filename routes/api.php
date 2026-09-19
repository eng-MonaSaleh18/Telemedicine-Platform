<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\MedicalArticleController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PasswordResetTokenController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SpecializationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('user/register', [AuthController::class, 'userRegister']);
Route::post('user/login', [AuthController::class, 'userLogin']);

Route::post('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

/* -----------------كل ما يخص المستخدم ذو الدور طبيب ----------------- */

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/verify-user-code', [AuthController::class, 'verifyUserCode']);
    Route::post('/resend-verification-code', [AuthController::class, 'resendVerificationCode']);

    Route::post('/chat/{chatId}/sendMessage', [MessageController::class, 'sendMessage']);

    Route::post('/auth/forgot-password', [PasswordResetTokenController::class, 'sendResetToken']);
    Route::post('/auth/verify-Reset-code', [PasswordResetTokenController::class, 'verifyResetToken']);
    Route::post('/auth/set-new-password', [PasswordResetTokenController::class, 'setNewPassword']);

    Route::group(['middleware' => 'role:doctor'], function () {
        Route::post('doctor/edit', [DoctorController::class, 'doctorEditInfo']);


        Route::post('create/appoientment', [AppointmentController::class, 'createAppointment']);
        Route::post('complete/appoientment', [AppointmentController::class, 'completeAppointment']);
        Route::get('available/appoientments', [AppointmentController::class, 'getAvailableAppointments']);
        Route::post('/cancel/appoientment-by-doctor', [AppointmentController::class, 'cancelAppointmentByDoctor']);


        Route::get('get/all/appoientments', [AppointmentController::class, 'getAllAppointments']);
        Route::post('/create-consultation', [ConsultationController::class, 'createConsultation']);
        Route::post('/create-prescription', [PrescriptionController::class, 'addAPrescription']);
        Route::get('/doctor-chats/getAll', [ChatController::class, 'getDoctorChats']);

        Route::get('/chat/{chatId}/getDoctorMessages', [MessageController::class, 'getDoctorMessages']);

        Route::get('/get-Complaints-Against-Me', [ComplaintController::class, 'complaintsAgainstMe']);
    });

    Route::group(['middleware' => 'role:patient'], function () {
        Route::post('patient/edit/{patient_id}', [PatientController::class, 'patientEditInfo']);
        Route::get('getAll/Specializations', [PatientController::class, 'getAllSpecializations']);
        Route::get('search/doctor', [PatientController::class, 'searchDoctorByName']);
        Route::get('filter/doctor', [PatientController::class, 'filterDoctor']);
        Route::post('/add-to-favorite', [PatientController::class, 'addToFavorite']);
        Route::post('/remove-from-favorite', [PatientController::class, 'removeFromFavorite']);
        Route::get('/get-All-Favorite-Doctor', [PatientController::class, 'getAllFavoriteDoctor']);
        Route::get('/get-All-doctor', [PatientController::class, 'getAllDoctor']);

        Route::post('book/appotientment', [AppointmentController::class, 'bookAnAppoientment']);
        Route::post('cancel/appotientment', [AppointmentController::class, 'cancelAppointment']);
        Route::get('get/MyAppoientments', [AppointmentController::class, 'getMyAppoientments']);
        Route::get('patient-chats/getALL', [ChatController::class, 'getPatientChats']);

        Route::get('/chat/{chatId}/getPatientMessages', [MessageController::class, 'getPatientMessages']);
        Route::post('/Rating-create', [RatingController::class, 'createRating']);

        Route::post('/Complaint-create', [ComplaintController::class, 'createComplaint']);
        Route::get('/get-My-Complaints', [ComplaintController::class, 'myComplaints']);
        

    });

    Route::group(['middleware' => 'role:admin'], function () {
        Route::post('store/specialization', [SpecializationController::class, 'storeSpecialization']);
        Route::get('getAll/specializations', [SpecializationController::class, 'getAllSpecialization']);
        Route::post('edit/specializations/{specialization_id}', [SpecializationController::class, 'editSpecialization']);
        Route::get('show/specializations/{specialization_id}', [SpecializationController::class, 'showSpecialization']);

        Route::post('/activate-doctor/{doctor_id}', [DoctorController::class, 'activateDoctor']);
        Route::post('/deactivate-doctor/{doctor_id}', [DoctorController::class, 'deactivateDoctor']);

        Route::post('store/faq', [FAQController::class, 'storeFaq']);
        Route::post('update/faq/{faq}', [FAQController::class, 'updateFaq']);
        Route::get('get/All/faq', [FAQController::class, 'getAllFaq']);
        Route::delete('delete/faq/{faq}', [FAQController::class, 'deleteFaq']);

        Route::post('store/medicalArticle', [MedicalArticleController::class, 'storeMedicalArticle']);
        Route::post('update/medicalArticle/{medicalArticle}', [MedicalArticleController::class, 'updateMedicalArticle']);
        Route::get('get/All/medicalArticle', [MedicalArticleController::class, 'getAllMedicalArticle']);
        Route::delete('delete/medicalArticle/{medicalArticle}', [MedicalArticleController::class, 'deleteMedicalArticle']);

        Route::get('/get-All-ComplaintsByAccused/{accused_id}', [ComplaintController::class, 'getComplaintsByAccused']);
        Route::get('/get-All-ComplaintsByComplainant/{Complainant_id}', [ComplaintController::class, 'getComplaintsByComplainant']);
        Route::delete('/delete-Complaint/{Complaint_id}', [ComplaintController::class, 'deleteComplaint']);
    });



    
});
