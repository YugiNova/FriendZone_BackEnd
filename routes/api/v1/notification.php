<?php
use App\Http\Controllers\API\v1\Client\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('notification')->name('notification.')->middleware(['auth','verified'])->group(function() {
   Route::get('/',[NotificationController::class,'getNotifications']);
   Route::patch('/{notification}',[NotificationController::class,'markAsRead']);
});
?>