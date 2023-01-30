<?php

use App\Events\VerifyDoctorEvent;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\TestController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ---------------- HomeController ----------------
Route::get('genaral_setting', [HomeController::class, 'GeneralSetting'])->name('general-setting');
Route::post('patient_appoinment', [HomeController::class, 'PatientAppoinment'])->name('patien-appoinment');
Route::post('doctor_detail', [HomeController::class, 'DoctorDetail'])->name('doctor-detail');
Route::post('appoinment_detail', [HomeController::class, 'AppoinmentDetail'])->name('appoinment-detail');
Route::post('get_specialities', [HomeController::class, 'GetSpecialities'])->name('get-specialities');
Route::post('updateNotificationStatus', [HomeController::class, 'UpdateNotificationStatus'])->name('update-notification-status');
Route::post('get_prescription_detail', [HomeController::class, 'GetPrescriptionDetail'])->name('get-prescription-detail');
Route::post('get_company', [HomeController::class, 'GetCompany'])->name('get-company');
Route::post('get_doctor', [HomeController::class, 'GetDoctor'])->name('get-doctor');
Route::post('searchDoctor', [HomeController::class, 'SearchDoctor'])->name('search-doctor');
Route::post('upcoming_appoinment', [HomeController::class, 'UpcomingAppoinment'])->name('upcoming-appoinment');
Route::post('get_medicine_history', [HomeController::class, 'GetMedicineHistory'])->name('get-medicine-history');
Route::post('make_appoinment', [HomeController::class, 'MakeAppoinment'])->name('make-appoinment');
Route::post('getTimeSlotByDoctorId', [HomeController::class, 'GetTimeSlotByDoctorId'])->name('get-time-slot-by-doctorId');

// ---------------- DoctorController ----------------

//register
Route::post('doctor_registration', [DoctorController::class, 'DoctorRegistration'])->name('doctor-registration');

//login
Route::post('doctor_login', [DoctorController::class, 'DoctorLogin'])->name('doctor-login');

Route::group(['middleware' => ['verified' , 'auth:doctor'] , 'prefix' => 'doctor'] , function (){
Route::post('doctor_forgot_password', [DoctorController::class, 'DoctorForgotPassword'])->name('doctor-forgot-password');
Route::post('doctor_upcoming_appoinment', [DoctorController::class, 'DoctorUpcomingAppoinment'])->name('doctor-upcoming-appoinment');
Route::post('appoinment_status_update', [DoctorController::class, 'AppoinmentStatusUpdate'])->name('appoinment-status-update');
Route::post('get_all_doctor_appoinment', [DoctorController::class, 'GetAllDoctorAppoinment'])->name('get-all-doctor-appoinment');
Route::post('get_doctor_appoinment', [DoctorController::class, 'GetDoctorAppoinment'])->name('get-doctor-appoinment');
Route::post('doctor_updateprofile', [DoctorController::class, 'DoctorUpdateProfile'])->name('doctor-update-profile');
Route::post('get_selected_timeslote', [DoctorController::class, 'GetSelectedTimeslote'])->name('get-selecte-timeslote');
Route::post('update_doctor_time_slots', [DoctorController::class, 'UpdateDoctorTimeSlots'])->name('update-doctor-time-slots');
Route::post('delete_doctor_time_slots', [DoctorController::class, 'DeleteDoctorTimeSlots'])->name('delete-doctor-time-slots');
Route::post('searchMedicine', [DoctorController::class, 'SearchMedicine'])->name('search-medicine');
Route::post('add_prescription', [DoctorController::class, 'AddPrescription'])->name('add-prescription');
Route::post('getPrescription', [DoctorController::class, 'GetPrescription'])->name('get-prescription');
Route::post('delete_prescription', [DoctorController::class, 'DeletePrescription'])->name('delete-prescription');
Route::post('update_appoinment', [DoctorController::class, 'UpdateAppoinment'])->name('update-appoinment');
});

// ---------------- UsersController ----------------
Route::post('login', [UsersController::class, 'Login'])->name('user-login');
Route::post('registration', [UsersController::class, 'Registration'])->name('user-registration');
Route::group(['middleware' => ['verified' , 'auth:patients'] , 'prefix' => 'user'] , function (){
    Route::post('password_change', [UsersController::class, 'PasswordChange'])->name('password-change');
    Route::post('profile', [UsersController::class, 'Profile'])->name('user-profile');
    Route::post('updateprofile', [UsersController::class, 'UpdateProfile'])->name('update-profile');
    Route::post('get_all_appoinment', [UsersController::class, 'GetAllAppoinment'])->name('get-all-appoinment');
    Route::post('get_patient_appoinment', [UsersController::class, 'GetPatientAppoinment'])->name('get-patient-appoinment');
});


// ---------------- TestController ----------------
Route::post('getTestTimeSlot', [TestController::class, 'GetTestTimeSlot'])->name('get-test-time-slot');
Route::post('make_test_appoinment', [TestController::class, 'MakeTestAppoinment'])->name('make-test-appoinment');
Route::post('get_test_patient_appoinment', [TestController::class, 'GetTestPatientAppoinment'])->name('get-test-patient-appoinment');


// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');


