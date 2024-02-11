<?php

use App\Http\Controllers\Api\Webapi\ContactController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\SubscriptionController;
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

// Route::post("/register", [AuthController::class, "register"]);

Route::post('/contact', [ContactController::class, 'contact']);

//category
Route::post('/add-category',[CategoryController::class,'addCategory']);

//package
Route::post('/add-package',[PackageController::class,'addPackage']);

//Route::middleware(['user'])->group(function () {
    //Filter and search
    Route::get('/filter-story-by-category',[StoryController::class,'filterStoryByCategory']);
    //story details in app
    Route::get('/story-details',[StoryController::class,'storyDetails']);
    //my subscription
    Route::get('/my-subscription',[SubscriptionController::class,'mySubscription']);
    // Subscription
    Route::post('/user-subscription',[SubscriptionController::class,'userSubscription']);
    //my story
    Route::get('/my-story',[StoryController::class,'myStory']);
    //delete story
    Route::get('/delete-story',[StoryController::class,'deleteStory']);
//});

//Route::middleware(['payment.user'])->group(function () {
    //add Story
    Route::post('/add-story',[StoryController::class,'addStory']);
// repost api
    Route::post('/edit-story',[StoryController::class,'editStory']);
    //pending story
    Route::get('/pending-story',[StoryController::class,'pendingStory']);
//});
