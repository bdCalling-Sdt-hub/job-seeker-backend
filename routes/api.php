<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

Route::group([

    ['middleware' => 'auth:api']

], function ($router) {

    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/email-verified", [AuthController::class, "emailVerified"]);
    Route::post("/login", [AuthController::class, "login"]);
    Route::get("/profile", [AuthController::class, "loggedUserData"]);
    Route::post('forget-pass', [AuthController::class, 'forgetPassword']);
    Route::post('/verified-checker', [AuthController::class, 'emailVerifiedForResetPass']);
    Route::post('/reset-pass', [AuthController::class, 'resetPassword']);
    Route::post('/update-pass', [AuthController::class, 'updatePassword']);
    Route::put("/profile/edit/{id}", [AuthController::class, 'editProfile']);
});

//Route::post("/register", [AuthController::class, "register"]);










































//category
Route::post('/add-category',[CategoryController::class,'addCategory']);

//package
Route::post('/add-package',[PackageController::class,'addPackage']);


// Subscription
Route::post('/user-subscription',[SubscriptionController::class,'userSubscription']);

//add Story
Route::post('/add-story',[StoryController::class,'addStory']);

//show Story

Route::get('/show-story',[Storycontroller::class,'showStory']);

Route::get('/test',[StoryController::class,'test']);
