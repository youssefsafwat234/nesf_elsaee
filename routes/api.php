<?php

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

// ==================================================== Authentication ===============================================
Route::post('auth/register', [\App\Http\Controllers\Api\Auth\RegisterController::class, 'register'])->name('register');
Route::post('auth/login', [\App\Http\Controllers\Api\Auth\LoginController::class, 'login'])->name('login');
Route::post('logout', [\App\Http\Controllers\Api\Auth\LoginController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
Route::post('auth/social-login', [\App\Http\Controllers\Api\Auth\SocialLoginController::class, 'socialLogin'])->name('social-login');
Route::post('auth/social-register', [\App\Http\Controllers\Api\Auth\SocialRegisterController::class, 'socialRegister'])->name('social-register');
Route::post('auth/forget-password', [\App\Http\Controllers\Api\Auth\ForgetPasswordController::class, 'forgetPassword'])->name('forget-password');
Route::post('auth/reset-password', [\App\Http\Controllers\Api\Auth\ResetPasswordController::class, 'resetPassword'])->name('reset-password');
Route::middleware('auth:sanctum')->name('api.')->group(function () {
    // ==================================================== Cities ===================================================
    Route::get('cities', [\App\Http\Controllers\Api\CityController::class, 'index'])->name('cities.index');
    // ==================================================== Categories ===================================================
    Route::get('categories', [\App\Http\Controllers\Api\CategoryController::class, 'index'])->name('categories.index');
    // ==================================================== neighbourhood ===================================================
    Route::get('neighbourhoods-by-city', [\App\Http\Controllers\Api\NeighbourhoodController::class, 'neighbourhoodByCity'])->name('neighbourhoods.neighbourhoodByCity');
    // ==================================================== advertisements ===================================================
    Route::post('advertisements', [\App\Http\Controllers\Api\AdvertisementController::class, 'store'])->name('advertisements.index');
    Route::get('advertisements/filter', [\App\Http\Controllers\Api\AdvertisementController::class, 'filter'])->name('advertisements.filter');
    // ==================================================== freelancers ===================================================
    Route::get('freelancers', [\App\Http\Controllers\Api\FreeLancerController::class, 'index'])->name('freelancers.index');
    // ==================================================== favorites ===================================================
    Route::get('favorites', [\App\Http\Controllers\Api\FavouriteController::class, 'index'])->name('favorites.index');
    Route::post('favorites/store', [\App\Http\Controllers\Api\FavouriteController::class, 'store'])->name('favorites.store');
    Route::delete('favorites/{id}/destroy', [\App\Http\Controllers\Api\FavouriteController::class, 'destroy'])->name('favorites.destroy');
    // ==================================================== users ===================================================
    Route::get('users/offices', [\App\Http\Controllers\Api\UserController::class, 'getAllOfficeAccounts'])->name('users.offices');
    Route::get('users/companies', [\App\Http\Controllers\Api\UserController::class, 'getAllCompanyAccounts'])->name('users.companies');
    Route::get('users/nearest-offices', [\App\Http\Controllers\Api\UserController::class, 'getNearestOfficeAccounts'])->name('users.nearest-offices');
    Route::get('users/nearest-companies', [\App\Http\Controllers\Api\UserController::class, 'getNearestCompanyAccounts'])->name('users.nearest-companies');
    // ==================================================== opinions ===================================================
    Route::post('send-opinion', [\App\Http\Controllers\Api\OpinionController::class, 'sendOpinion'])->name('opinions.sendOpinion');
    Route::post('send-user-opinion-response', [\App\Http\Controllers\Api\OpinionController::class, 'sendOpinionResponse'])->name('opinions.sendOpinionResponse');
    Route::get('get-user-opinions', [\App\Http\Controllers\Api\OpinionController::class, 'getUserOpinions'])->name('opinions.getUserOpinions');
});


Route::post('nafath/login', [\App\Http\Controllers\NafathController::class, 'sendLoginRequest']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
