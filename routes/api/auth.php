<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function() {

    Route::post('/login',[AuthController::class,'login'])->name('login');
    Route::post('/register',[AuthController::class,'register'])->name('register');
    Route::get('/email/verify/{id}/{token}',[AuthController::class,'verifyMail'])->name('email.verify.link');
    
    Route::post('/password/reset/',[AuthController::class,'sendResetPasswordMail'])->name('password.reset.send');
    Route::get('/password/reset/{id}',[AuthController::class,'resetPassword'])->name('password.reset.link');
    
    Route::middleware('auth')->group(function(){
        Route::get('/email/verify',[AuthController::class,'sendVerificationMail'])->name('email.verify.send');
        Route::middleware('verified')->group(function(){
            Route::get('/getdata',[AuthController::class,'getdata'])->name('getdata');
            Route::post('/logout',[AuthController::class,'logout'])->name('logout');
            
        });       
    });
});

?>
