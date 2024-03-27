<?php

use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CanditedController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeleteUserController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RulesRegulationController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::group([
    ['middleware' => 'auth:api']
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/email-verified', [AuthController::class, 'emailVerified']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'loggedUserData']);
    Route::post('forget-pass', [AuthController::class, 'forgetPassword']);
    Route::post('/verified-checker', [AuthController::class, 'emailVerifiedForResetPass']);
    Route::post('/reset-pass', [AuthController::class, 'resetPassword']);
    Route::post('/update-pass', [AuthController::class, 'updatePassword']);
    Route::put('/profile/edit/{id}', [AuthController::class, 'editProfile']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

    Route::post('/social-login', [SocialLoginCOntroller::class, 'socialLogin']);
    // notification
    Route::get('/notification', [NotificationController::class, 'notification']);

    Route::get('/user-read-notification', [NotificationController::class, 'userReadNotification']);
});

Route::get('/show-package', [PackageController::class, 'showPackage']);
// category
Route::post('/add-category', [CategoryController::class, 'addCategory']);
Route::get('/show/category', [CategoryController::class, 'show_category']);
// package
Route::post('/add-package', [PackageController::class, 'addPackage']);

Route::middleware(['user', 'auth:api'])->group(function () {
    // my subscription
    Route::get('/my-subscription', [SubscriptionController::class, 'mySubscription']);
    Route::get('/upgrade-subscription', [SubscriptionController::class, 'upgradeSubscription']);
    // Subscription
    Route::post('/user-subscription', [SubscriptionController::class, 'userSubscription']);

    // Subscription
    Route::post('/user-subscription', [SubscriptionController::class, 'userSubscription']);
    Route::get('/terms-condition', [RulesRegulationController::class, 'termsCondition']);
    Route::get('/privacy-policy', [RulesRegulationController::class, 'privacyPolicy']);
    Route::get('/about-us', [RulesRegulationController::class, 'aboutUs']);

    // delete user
    Route::post('delete-user', [DeleteUserController::class, 'deleteUser']);

    // notification

    Route::get('/read-unread', [NotificationController::class, 'markRead']);

    // ================ Application Now ==================//

    Route::post('job/application', [CanditedController::class, 'apply_now']);
});

Route::middleware(['payment.user', 'auth:api'])->group(function () {});

Route::middleware(['admin', 'auth:api'])->group(function () {
    // ================== Admin Api ====================//

    // update category
    Route::post('/update-category/{id}', [CategoryController::class, 'updateCategory']);
    // notification

    Route::get('/admin-notification', [NotificationController::class, 'adminNotification']);
    Route::get('/read-notification', [NotificationController::class, 'readNotificationById']);
});

Route::middleware(['super.admin', 'auth:api'])->group(function () {
    // super admin
    Route::post('/add-admin', [AuthAdminController::class, 'addAdmin']);
    Route::get('/show-admin', [AuthAdminController::class, 'showAdmin']);
    Route::get('/delete-admin/{id}', [AuthAdminController::class, 'deleteAdmin']);
});

Route::get('/notification-event', [NotificationController::class, 'notificationEvent']);

// Emplyer section //

Route::middleware(['recruiter', 'auth:api'])->group(function () {
    Route::post('/create/recruiter', [EmployerController::class, 'create_recruiter']);
    Route::get('/show/recruiter', [EmployerController::class, 'show_recruiter']);
    Route::get('/edit/recruiter/{id}', [EmployerController::class, 'edite_recruiter']);
    Route::post('/update/recruiter', [EmployerController::class, 'update_recrioter']);
    Route::post('/update/logo', [EmployerController::class, 'updateLogo']);
    Route::get('/delete/recruiter/{id}', [EmployerController::class, 'delete_recruiter']);
    Route::get('/delete/log/{id}', [EmployerController::class, 'logodestroy']);

    // JOB POST //

    Route::post('/create/job', [JobPostController::class, 'create_job']);
    Route::get('/edit/job/{id}', [JobPostController::class, 'edit_job']);
    Route::post('/update/job', [JobPostController::class, 'update_job']);
    Route::get('/delete/job/{id}', [JobPostController::class, 'delete_job']);
    Route::get('/show/job', [JobPostController::class, 'show_job']);
    Route::get('/application/job', [JobPostController::class, 'apply_job_show']);
});
