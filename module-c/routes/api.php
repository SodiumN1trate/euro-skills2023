<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatterBlastController;
use App\Http\Controllers\Api\DreamWeaverController;
use App\Http\Controllers\Api\MindReaderController;
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

Route::middleware('auth_api')->group(function () {
    Route::post('/chat/conversation',[ChatterBlastController::class,'startConversation']);
    Route::get('/chat/conversation/{conversation_id}',[ChatterBlastController::class,'getConversation']);
    Route::put('/chat/conversation/{conversation_id}',[ChatterBlastController::class,'continueConversation']);


    Route::post('/imagegeneration/generate', [DreamWeaverController::class, 'generate']);
    Route::get('/imagegeneration/status/{job_id}', [DreamWeaverController::class, 'status']);
    Route::get('/imagegeneration/result/{job_id}', [DreamWeaverController::class, 'result']);
    Route::post('/imagegeneration/upscale', [DreamWeaverController::class, 'upscale']);
    Route::post('/imagegeneration/zoom/in', [DreamWeaverController::class, 'zoomin']);
    Route::post('/imagegeneration/zoom/out', [DreamWeaverController::class, 'zoomout']);

    Route::post('/imagerecognition/recognize', [MindReaderController::class, 'recognize']);
});
