<?php

use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookMarkController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CanditedController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeleteUserController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmplyDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RulesRegulationController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PopularJobController;

Route::group([
    ['middleware' => 'auth:api']
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/email-verified', [AuthController::class, 'emailVerified']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'loggedUserData']);
    Route::post('/forget-pass', [AuthController::class, 'forgetPassword']);
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
Route::get('/single-package', [PackageController::class, 'singlePackage']);
// category
Route::post('/add-category', [CategoryController::class, 'addCategory']);
Route::get('/show/category', [CategoryController::class, 'show_category']);
// package
Route::post('/add-package', [PackageController::class, 'addPackage']);

Route::middleware(['user', 'auth:api'])->group(function () {

    Route::get('/popular-job-post',[PopularJobController::class,'popularJobPost']);

    //view job post
    Route::get('/job-details',[PopularJobController::class,'jobDetails']);

    // my subscription
    Route::get('/my-subscription', [SubscriptionController::class, 'mySubscription']);
    Route::get('/upgrade-subscription', [SubscriptionController::class, 'upgradeSubscription']);

    // -----------------Candidate-------------------------

    Route::post('/add-profile-info', [CandidateController::class, 'addProfileInfo']);
    Route::post('/update-profile-info', [CandidateController::class, 'updateProfileInfo']);
    Route::post('/add-experience-info', [CandidateController::class, 'addExperienceInfo']);
    Route::post('/update-experience-info', [CandidateController::class, 'updateExperienceInfo']);
    Route::post('/add-education-info', [CandidateController::class, 'addEducationInfo']);
    Route::post('/update-education-info', [CandidateController::class, 'updateEducationInfo']);
    Route::post('/add-training-info', [CandidateController::class, 'addTrainingInfo']);
    Route::post('/update-training-info', [CandidateController::class, 'updateTrainingInfo']);
    Route::post('/add-interest-info', [CandidateController::class, 'addInterestInfo']);
    Route::post('/update-interest-info', [CandidateController::class, 'updateInterestInfo']);
    Route::get('/profile-info', [CandidateController::class, 'getProfileInfo']);

    //-----------------filter-----------------
    Route::get('/job-filter', [HomeController::class, 'jobFilter']);
    //book mark job
    Route::post('toggle-bookmark', [BookMarkController::class, 'toggleBookmark']);
    Route::get('bookmark-data', [BookMarkController::class, 'bookmarksData']);

    // ---------Job Gallery ----------
    Route::get('/job-gallery', [CandidateController::class, 'jobGallery']);

    //show category and count
    Route::get('/category-job-post-count', [HomeController::class, 'showCategoryandCount']);

    //category wise job list show
    Route::get('category-wise-job-list', [HomeController::class, 'categoryWiseJobPost']);
    Route::get('single-category-wise-job-list', [HomeController::class, 'SingleCategoryWiseJobPost']);
    //single category wise show job list
    Route::get('category-wise-job-list', [HomeController::class, 'categoryIdWiseJobPost']);

    Route::get('company-wise-job-list', [HomeController::class, 'companyWiseJobPost']);


    // my subscription
    Route::get('/my-subscription', [SubscriptionController::class, 'mySubscription']);
    Route::get('/upgrade-subscription', [SubscriptionController::class, 'upgradeSubscription']);

    // Subscription
    Route::post('/user-subscription', [SubscriptionController::class, 'userSubscription']);

    Route::get('/terms-condition', [RulesRegulationController::class, 'termsCondition']);
    Route::get('/privacy-policy', [RulesRegulationController::class, 'privacyPolicy']);
    Route::get('/about-us', [RulesRegulationController::class, 'aboutUs']);

    // delete user
    Route::get('delete-user', [DeleteUserController::class, 'deleteUser']);

    // notification

    Route::get('/read-unread', [NotificationController::class, 'markRead']);

    // ================ Application Now ==================//

    Route::post('job/application', [CanditedController::class, 'apply_now']);

});

Route::middleware(['admin', 'auth:api'])->group(function () {
        // ================== Admin Api ====================//
    Route::get('package-wise-company-subscription', [DashboardController::class, 'packageWiseCompanySubscription']);

    // apt
    Route::post('about-us', [RulesRegulationController::class, 'addAboutUs']);
    Route::post('update-about-us/{id}', [RulesRegulationController::class, 'updateAboutUs']);
    Route::post('add-privacy-policy', [RulesRegulationController::class, 'addPrivacyPolicy']);
    Route::post('update-privacy-policy/{id}', [RulesRegulationController::class, 'updatePrivacyPolicy']);
    Route::post('add-terms-condition', [RulesRegulationController::class, 'addTermsAndConditions']);
    Route::post('update-terms-condition/{id}', [RulesRegulationController::class, 'updateTermsAndConditions']);

    // update category
    Route::post('/update-category/{id}', [CategoryController::class, 'updateCategory']);

    //    Route::post('send-message-admin',[ContactController::class,'sendMessageToAdmin']);
    Route::post('send-message-user', [ContactController::class, 'sendMessageToUser']);
    Route::get('show-message', [ContactController::class, 'showAllMessage']);
    Route::get('delete-message', [ContactController::class, 'deleteMessage']);

    // chart
    Route::get('month-wise-employer/{year}', [DashboardController::class, 'monthWiseEmployer']);
    Route::get('month-wise-jobpost/{year}', [DashboardController::class, 'monthWiseJobPost']);
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    Route::get('employer-list', [DashboardController::class, 'employerList']);
    Route::get('company-wise-subscription', [DashboardController::class, 'companyWiseSubscription']);
    Route::get('package-wise-company-job-list', [DashboardController::class, 'packageWiseCompanyJobList']);

    // approve job post
    Route::get('approve-job-post', [DashboardController::class, 'approveJobPost']);

    // block recruiter
    Route::get('block-recruiter', [DashboardController::class, 'blockRecruiter']);
    // report employer
    Route::post('report-employer', [DashboardController::class, 'reportEmployer']);
    // job list
    Route::get('job-list', [DashboardController::class, 'jobList']);
    Route::get('single-job-list', [DashboardController::class, 'jobDetails']);
    Route::post('/update-category/{id}', [CategoryController::class, 'updateCategory']);
    // ================== Dashboard Api ====================//

    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    Route::get('employer-list', [DashboardController::class, 'employerList']);
    Route::get('company-wise-subscription', [DashboardController::class, 'companyWiseSubscription']);

    // -----------------Package -------------------
//    Route::get('show-package', [PackageController::class, 'showPackage']);
    Route::post('add-package', [PackageController::class, 'addPackage']);
    Route::post('update-package', [PackageController::class, 'updatePackage']);
    Route::get('delete-package', [PackageController::class, 'deletePackage']);

    // -----------------Category --------------------
    Route::post('add-category', [CategoryController::class, 'addCategory']);
    Route::post('update-category', [CategoryController::class, 'updateCategory']);
    Route::get('delete-category', [CategoryController::class, 'deleteCategory']);

    // -----------------Package -------------------
//    Route::get('show-package', [PackageController::class, 'showPackage']);
    Route::post('add-package', [PackageController::class, 'addPackage']);
    Route::post('update-package', [PackageController::class, 'updatePackage']);
    Route::get('delete-package', [PackageController::class, 'deletePackage']);

    // notification

    Route::get('/admin-notification', [NotificationController::class, 'adminNotification']);
    Route::get('/read-notification', [NotificationController::class, 'readNotificationById']);

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
    // contact with admin
    Route::post('send-message-admin', [ContactController::class, 'sendMessageToAdmin']);

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
    Route::get('/show/subscribe/package', [JobPostController::class, 'show_subscribe_package']);
    Route::get('/application/job', [JobPostController::class, 'apply_job_show']);
    Route::get('/notification-event', [NotificationController::class, 'notificationEvent']);

    // Subscription
    Route::post('/recruiter-subscription', [SubscriptionController::class, 'recruiterSubscription']);

    // Dashboard //
    Route::get('/counting', [EmplyDashboardController::class, 'Counting_dashboard']);
    Route::get('/coust/ratio', [EmplyDashboardController::class, 'yearly_avg_coust']);
    Route::get('/application/list', [EmplyDashboardController::class, 'apply_list']);
    Route::get('/apply/details/{applyId}', [EmplyDashboardController::class, 'applyDetails']);
    Route::get('/cv', [EmplyDashboardController::class, 'CV']);
    Route::post('/apply/status', [EmplyDashboardController::class, 'applyStatus']);
    Route::post('/send/mail', [EmplyDashboardController::class, 'select_candited_send_mail']);
    Route::post('/contact/mail', [EmplyDashboardController::class, 'send_mail_data']);
    Route::get('/subscription', [EmplyDashboardController::class, 'subscription']);
    Route::get('/subscription/details/{id}', [EmplyDashboardController::class, 'subscription_details']);

    // ==================CONTACT PAGE =============== //
    Route::post('/contact/message', [EmplyDashboardController::class, 'post_contact']);
    Route::get('/inbox/message', [EmplyDashboardController::class, 'message_inbox']);
    Route::get('/send/message', [EmplyDashboardController::class, 'send_message']);

    // ===================FILTERING==========================//

    Route::get('/job/search', [EmplyDashboardController::class, 'job_search']);
    Route::get('/job/filter', [EmplyDashboardController::class, 'job_filter']);
});

Route::middleware(['all.user.type'])->group(function () {
});

Route::get('/terms-condition', [RulesRegulationController::class, 'termsCondition']);
Route::get('/privacy-policy', [RulesRegulationController::class, 'privacyPolicy']);
Route::get('/about-us', [RulesRegulationController::class, 'aboutUs']);
Route::get('show-category', [CategoryController::class, 'showCategory']);

