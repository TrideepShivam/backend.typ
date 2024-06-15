<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\TestController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    Route::post('logout', [AuthController::class,'logout']);
});
Route::group(['middleware' => 'api'],function ($router) {
    Route::post('stories', [StoryController::class,'index']);
    Route::post('story', [StoryController::class,'show']);
});
Route::group(['middleware' => 'api'],function($router){
    Route::post('store-test', [TestController::class,'store']);
    Route::post('get-attempts', [TestController::class,'show']);
});