<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SupportController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    // Auth utgan userni logout
    Route::post('logout', [AuthController::class, 'logout']);

    // Auth utgan user
    Route::get('user', [AuthController::class, 'user']);

    Route::middleware(['check.blocked'])->group(function () {
        // Auth utgan userni message
        Route::post('message', [MessageController::class, 'store']);

        // Auth utgan userni message role qarab kirish kk
        Route::post('store', [TicketController::class, 'store']);

        // Auth utgan userni message va qaysi ticketga yozayotgani
        Route::get('ticket', [TicketController::class, 'index']);

        // Auth utgan
        Route::get('support', [SupportController::class, 'index']);
        Route::post('users/{user_id}/block', [SupportController::class, 'blockUser']);
        Route::post('users/{user_id}/unblock', [SupportController::class, 'unBlock']);

        // Auth utgan userni message
        Route::patch('tickets/{ticket_id}/assign', [TicketController::class, 'assignTicketToSupport']);
        Route::patch('tickets/{ticket_id}/close', [TicketController::class, 'closeTicket']);

        // rating
        Route::post('rating/store', [RatingController::class, 'store']);
        Route::get('ratings', [RatingController::class, 'index']);
        Route::get('rating/{ticket_id}', [RatingController::class, 'show']);
    });

});
