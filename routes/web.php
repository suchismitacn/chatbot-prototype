<?php

use App\Http\Controllers\BotManController;
use App\Http\Controllers\ConversationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'chat'], function () {
    Route::post('/send-message', [ConversationController::class, 'sendMessage']);
    Route::post('/fetch-messages', [ConversationController::class, 'fetchMessages']);
    Route::get('/{from}/{chatId}', [ConversationController::class, 'initChat'])->name('live-chat');
    Route::get('/admin', [ConversationController::class, 'initAdminChat'])->name('admin-live-chat');
    Route::post('/fetch-users', [ConversationController::class, 'fetchUsers']);

});


Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
Route::get('/botman/tinker', [BotManController::class, 'tinker']);
