<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApartmentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('/apartments', ApartmentController::class);

Route::get('/apartments/{lat}/{lon}/{distance}', [ApartmentController::class, 'advancedSearch'])->name('advancedSearch');

Route::get('/sponsored/{plan?}/{max?}/{random?}', [ApartmentController::class, 'getSponsored']);
Route::get('/standard/{max?}/{random?}', [ApartmentController::class, 'getStandard']);
