<?php

use App\Http\Controllers\Api\Addmin\UserController;
use App\Http\Controllers\Api\Webapi\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoryController;
use Illuminate\Http\Request;
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
});

// ================ WEB API ================== //

Route::post('/contact', [ContactController::class, 'contact']);
Route::get('/recent/story', [ContactController::class, 'recentStory']);
Route::get('/all/story', [ContactController::class, 'allStory']);
Route::get('/story/details/{id}', [ContactController::class, 'storyDetails']);
Route::get('/about', [ContactController::class, 'about']);
Route::get('/pricing', [ContactController::class, 'priceing']);
Route::get('/terms/condition', [ContactController::class, 'terms_condition']);
Route::get('/privacy/policy', [ContactController::class, 'privacy']);

// ================== Admin Api ====================//

Route::get('/user/list', [UserController::class, 'userList']);
