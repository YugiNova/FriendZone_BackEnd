<?php
use App\Http\Controllers\API\v1\Client\FriendshipController;
use App\Http\Controllers\API\v1\Client\ReactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('like')->name('like.')->middleware(['auth','verified'])->group(function() {
  Route::post('/',[ReactionController::class,'createReaction']);
  Route::delete('/',[ReactionController::class,'deleteReaction']);
});
?>