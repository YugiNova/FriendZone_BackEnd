<?php

use App\Http\Controllers\API\v1\Client\CommentController;
use Illuminate\Support\Facades\Route;

Route::prefix('comment')->name('comment.')->middleware(['auth','verified'])->group(function() {
  Route::post('/',[CommentController::class,'createComment']);
  Route::delete('/',[CommentController::class,'deleteComment']);
  Route::get('/post/{post}',[CommentController::class,'getCommentsByPost']);
  Route::get('/post/{post}/comment/{comment}',[CommentController::class,'getCommentsByComment']);
});
?>