<?php

use App\Http\Controllers\LineWebhookController;
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

Route::get('/line/message/send', [LineWebhookController::class, 'sendMessage']);
Route::get('/line/message/image', [LineWebhookController::class, 'sendImage']);
Route::get('/line/message/stamp', [LineWebhookController::class, 'sendStamp']);
Route::get('/line/message/flex', [LineWebhookController::class, 'sendFlexMessage']);
Route::get('/line/message/profile', [LineWebhookController::class, 'getProfile']);
Route::post('/line/webhook/callback', [LineWebhookController::class, 'callback']);
