<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\PuntajeController;
use App\Http\Controllers\API\answersubmit;
use App\Http\Controllers\API\Salacontroller;
use App\Http\Controllers\API\SalaAdivinanzaController;
use App\Http\Controllers\API\AdivinanzaController;
use App\Http\Controllers\API\WinController;
use App\Http\Controllers\API\UsuarioController;


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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
   
});
Route::middleware(['auth:sanctum'])->group(function () {
Route::Resource('/salas', SalaController::class);
Route::post('/salas/adivinanzas/game',[GameController::class, 'adivinanzasPorSala']);
Route::post('/send-message', [ChatController::class, 'sendMessage']);
Route::post('/game-room/{roomId}', [GameController::class, 'joinGameRoom']);
Route::post('/send-responseriddle', [answersubmit::class, 'AnswerSubmitvalidate']);
Route::post('/winner', [WinController::class, 'WinnerGameRoom']);
Route::get('/salasxadvinanzas',[SalaAdivinanzaController::class,'adivinanzasPorSala']);
Route::Resource('/adivinanzas', AdivinanzaController::class);
Route::get('/puntajes',[PuntajeController::class, 'puntajes']);
Route::Resource('/salas/adivinanzas',SalaAdivinanzaController::class);
Route::resource('/users', UsuarioController::class);
});
Broadcast::routes(['middleware' => ['api', 'auth:sanctum']]);






