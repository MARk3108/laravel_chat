<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
   Route::put('/profile', [AuthController::class, 'updateProfile']);
   Route::post('/messages', [MessageController::class, 'send']);
   Route::get('/messages/inbox/{id}', [MessageController::class, 'inbox']);
   Route::put('/messages/{id}', [MessageController::class, 'update']);
   Route::delete('/messages/{id}', [MessageController::class, 'delete']);
});
