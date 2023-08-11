<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


require __DIR__.'/api/v1/auth.php';
require __DIR__.'/api/v1/user.php';
require __DIR__.'/api/v1/friendship.php';
require __DIR__.'/api/v1/notification.php';
require __DIR__.'/api/v1/post.php';
require __DIR__.'/api/v1/like.php';
require __DIR__.'/api/v1/comment.php';
?>