<?php
use App\Http\Controllers\API\v1\Client\FriendshipController;
use Illuminate\Support\Facades\Route;

Route::prefix('friendship')->name('friendship.')->middleware(['auth','verified'])->group(function() {
   Route::post('/send-request',[FriendshipController::class,'sendFriendRequest']);
   Route::post('/accept-request',[FriendshipController::class,'acceptFriendRequest']);
   Route::post('/remove-request',[FriendshipController::class,'removeFriendRequest']);
   Route::get('/friend-requests',[FriendshipController::class,'getFriendRequests']);
});
?>