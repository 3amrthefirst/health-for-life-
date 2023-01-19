<?php

use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SpecialtiesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AppointmentController;

Route::get('/admin/login', [LoginController::class, 'index'])->name('Login');
Route::post('admin/login', [LoginController::class, 'login'])->name('LoginGet');
Route::get('logout', [LoginController::class, 'logout'])->name('Logout');

// Pages
Route::get('about-us', [LoginController::class, 'Page'])->name('AboutUS');
Route::get('privacy-policy', [LoginController::class, 'Page'])->name('privacyPolicy');
Route::get('terms-and-conditions', [LoginController::class, 'Page'])->name('TermsAndConditions');

Route::group(['prefix' => 'admin', 'middleware' => 'adminauth'], function () {

    Route::group(['prefix' => 'admin', 'middleware' => 'checkadmin'], function () {

        Route::any('notification/save', [NotificationController::class, 'store'])->name('NotificationSave');
        Route::post('notificationsetting/update', [NotificationController::class, 'update'])->name('NotificationUpdate');

        Route::post('setting/Save', [SettingController::class, 'save'])->name('SettingSave');
        Route::post('passwordsetting', [SettingController::class, 'passwordchange'])->name('PwdSave');
        Route::post('Admob/update', [SettingController::class, 'admobupdate'])->name('AdmobUpdate');
        Route::post('iso/update', [SettingController::class, 'isoupdate'])->name('IsoUpdate');
        Route::post('facebook/update', [SettingController::class, 'facebookadmob'])->name('facebookupdate');
        Route::post('facebook/iso', [SettingController::class, 'facebookiso'])->name('facebookiso');
        Route::post('EmailSetting/update', [SettingController::class, 'emailsettingupdate'])->name('EmailUpdate');
        
        Route::get('doctor/generateslot', [DoctorController::class, 'generateslot'])->name('doctor.GenerateSlot');
        Route::any('doctor/slot', [DoctorController::class, 'doctor_timing'])->name('AppointmentSlot');
    });

    Route::get('language/{id}', [DashboardController::class, 'language'])->name('Language');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('notification', [NotificationController::class, 'index'])->name('Notification');
    Route::get('notification/data', [NotificationController::class, 'data'])->name('Notificationdata');
    Route::get('notification/add', [NotificationController::class, 'create'])->name('NotificationAdd');
    Route::get('notificationsetting', [NotificationController::class, 'notificationsetting'])->name('NotificationSetting');
    Route::resource('doctor', DoctorController::class);
    Route::resource('specialties', SpecialtiesController::class);
    
    Route::resource('company', CompanyController::class);
    Route::resource('patient', PatientController::class);
    Route::resource('review', ReviewController::class);
    Route::any('patinet/status', [PatientController::class, 'change_status'])->name('StatusChange');
    Route::post('Cucrency/save', [SettingController::class, 'currencysave'])->name('CurrencySave');
    
    Route::get('setting', [SettingController::class, 'index'])->name('Setting');
    Route::get('EmailSetting', [SettingController::class, 'emailsetting'])->name('EmailSetting');
    
    Route::resource('page', PageController::class);
    
    Route::resource('appointment', AppointmentController::class);    
    Route::resource('medicine', MedicineController::class);
});
