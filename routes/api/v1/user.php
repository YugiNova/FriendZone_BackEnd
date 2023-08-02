<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\Client\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->middleware(['auth','verified'])->group(function() {
    Route::get('/profile/{slug}',[UserController::class,'getProfile'])->name('profile');
    Route::patch('/profile/{slug}/theme',[UserController::class,'updateTheme'])->name('profile.theme.update');
    Route::patch('/profile/{slug}/color',[UserController::class,'updateColor'])->name('profile.color.update');
});

?>
