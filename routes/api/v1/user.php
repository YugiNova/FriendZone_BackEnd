<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\Client\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->middleware(['auth','verified'])->group(function() {
    Route::get('/',[UserController::class,'getUserList']);
    Route::get('/profile/{slug}',[UserController::class,'getProfile'])->name('profile');

    //User
    Route::post('/{user}/avatar',[UserController::class,'updateAvatar']);

    //Profile
    Route::patch('/profile/{slug}/theme',[UserController::class,'updateTheme'])->name('profile.theme.update');
    Route::patch('/profile/{slug}/color',[UserController::class,'updateColor'])->name('profile.color.update');
    Route::patch('/profile/{profile}/dob',[UserController::class,'updateDob'])->name('profile.dob.update');
    Route::patch('/profile/{profile}/introduce',[UserController::class,'updateIntroduce'])->name('profile.introduce.update');
    Route::post('/profile/{profile}/coverImage',[UserController::class,'updateCoverImage']);

    //Contact
    Route::post('/contact',[UserController::class,'createContact']);
    Route::patch('/contact/{contact}',[UserController::class,'updateContact']);
    Route::delete('/contact/{contact}',[UserController::class,'deleteContact']);

    //Place
    Route::post('/place',[UserController::class,'createPlace']);
    Route::patch('/place/{place}',[UserController::class,'updatePlace']);
    Route::delete('/place/{place}',[UserController::class,'deletePlace']);

    //Work and Education
    Route::post('/work_education',[UserController::class,'createWorkEducation']);
    Route::patch('/work_education/{workeducation}',[UserController::class,'updateWorkEducation']);
    Route::delete('/work_education/{workeducation}',[UserController::class,'deleteWorkEducation']);

});
