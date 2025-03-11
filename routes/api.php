<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SenderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\PrintHistoryController;

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

// Middleware để lấy thông tin người dùng hiện tại
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route không yêu cầu xác thực
Route::get('/form', function () {
    return response()->json(['message' => 'Data Entry Form']);
});

// Route xử lý việc gửi dữ liệu từ form
Route::post('/submit-form', function (Request $request) {
    // Logic để xử lý dữ liệu từ form
    return response()->json(['message' => 'Data submitted successfully!'], 200);
});

// Route có yêu cầu xác thực

    Route::middleware(['admin'])->group(function () {
        Route::get('/manage', function () {
            return response()->json(['message' => 'Manage'], 200);
        })->name('manage');

        Route::post('/users', [UserController::class, 'store']);
    });

    Route::post('/recipients', [RecipientController::class, 'store'])->name('recipients.store');
    Route::put('/recipients/{id}', [RecipientController::class, 'update'])->name('recipients.update');
    Route::get('/contacts', [RecipientController::class, 'index'])->name('contacts.index');
    Route::post('/recipients', [RecipientController::class, 'store'])->name('recipients.store');

// Các route cho Sender
Route::middleware('api')->group(function () {
    Route::get('/senders', [SenderController::class, 'index']); 
    Route::post('/senders', [SenderController::class, 'store']);
});