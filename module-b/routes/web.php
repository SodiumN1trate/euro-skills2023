<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\TokenController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/workspaces', [WorkspaceController::class, 'index'])->name('workspaces');
Route::get('/workspaces/show/{id}', [WorkspaceController::class, 'showView'])->name('workspaces.show');
Route::get('/workspaces/create', [WorkspaceController::class, 'createView'])->name('workspaces.create');
Route::post('/workspaces/create/process', [WorkspaceController::class, 'create'])->name('workspaces.create.process');
Route::post('/workspaces/edit/{id}/process', [WorkspaceController::class, 'edit'])->name('workspaces.update.process');

Route::get('/workspaces/bill/{id}', [WorkspaceController::class, 'bill'])->name('workspaces.bill.show');


Route::get('/token/{id}/revoke', [TokenController::class, 'revoke'])->name('token.revoke');
Route::post('/token/create/{id}', [TokenController::class, 'create'])->name('token.create');

Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
