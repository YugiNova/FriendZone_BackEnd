<?php

use App\Http\Controllers\API\v1\Client\PostController;
use Illuminate\Support\Facades\Route;

Route::prefix('post')->name('post.')->middleware(['auth','verified'])->group(function() {
   Route::get('/',[PostController::class,'getPosts']);
   Route::get('/{user}',[PostController::class,'getPostsByUserProfile']);
   Route::post('/',[PostController::class,'createPost']);
});
?>