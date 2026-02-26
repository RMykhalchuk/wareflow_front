<?php


use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Common routes
Route::post('/login', [AuthController::class, 'login']);

Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

Route::prefix('terminal')->group(base_path('routes/api/terminal.php'));


Route::prefix('public')->group(base_path('routes/api/public.php'));


