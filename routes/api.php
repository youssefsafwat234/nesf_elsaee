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
    Route::post('advertisements/filter', [\App\Http\Controllers\Api\AdvertisementController::class, 'filter'])->name('advertisements.filter');
    Route::get('advertisements/get-filter-data', [\App\Http\Controllers\Api\AdvertisementController::class, 'getFilterData'])->name('advertisements.get-filter-data');
    Route::get('advertisements/get-advertisements-by-city', [\App\Http\Controllers\Api\AdvertisementController::class, 'getAdvertisementsByCity'])->name('advertisements.getAdvertisementsByCity');

    // ==================================================== freelancers ===================================================
    Route::get('freelancers', [\App\Http\Controllers\Api\FreeLancerController::class, 'index'])->name('freelancers.index');
    // ==================================================== favorites ===================================================
    Route::get('favorites', [\App\Http\Controllers\Api\FavouriteController::class, 'index'])->name('favorites.index');
    Route::post('favorites/store', [\App\Http\Controllers\Api\FavouriteController::class, 'store'])->name('favorites.store');
    Route::delete('favorites/{id}/destroy', [\App\Http\Controllers\Api\FavouriteController::class, 'destroy'])->name('favorites.destroy');
    // ==================================================== users ===================================================
    Route::get('users/offices', [\App\Http\Controllers\Api\UserController::class, 'getAllOfficeAccounts'])->name('users.offices');
    Route::get('users/companies', [\App\Http\Controllers\Api\UserController::class, 'getAllCompanyAccounts'])->name('users.companies');
    Route::delete('users/destroy', [\App\Http\Controllers\Api\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('users/nearest-offices', [\App\Http\Controllers\Api\UserController::class, 'getNearestOfficeAccounts'])->name('users.nearest-offices');
    Route::get('users/nearest-freelancers', [\App\Http\Controllers\Api\UserController::class, 'getNearestFreelancerAccounts'])->name('users.nearest-freelancers');
    Route::get('users/nearest-companies', [\App\Http\Controllers\Api\UserController::class, 'getNearestCompanyAccounts'])->name('users.nearest-companies');
    Route::post('users/update-profile', [\App\Http\Controllers\Api\UserController::class, 'updateProfile'])->name('users.update-profile');
    Route::post('users/update-user-password', [\App\Http\Controllers\Api\UserController::class, 'updateUserPassword'])->name('users.update-user-password');
    // ==================================================== opinions ===================================================
    Route::post('send-opinion', [\App\Http\Controllers\Api\OpinionController::class, 'sendOpinion'])->name('opinions.sendOpinion');
    Route::post('send-user-opinion-response', [\App\Http\Controllers\Api\OpinionController::class, 'sendOpinionResponse'])->name('opinions.sendOpinionResponse');
    Route::get('get-user-opinions', [\App\Http\Controllers\Api\OpinionController::class, 'getUserOpinions'])->name('opinions.getUserOpinions');
    // ==================================================== chats ===================================================
    Route::apiResource('chats', \App\Http\Controllers\Api\ChatController::class)->only(['index', 'show', 'store']);
    // ==================================================== chat Messages ===================================================
    Route::apiResource('chat-messages', \App\Http\Controllers\Api\ChatMessageController::class)->only(['index', 'store']);

});
// ==================================================== users that appear i the login page ===================================================
Route::get('users/lowerAccounts', [\App\Http\Controllers\Api\UserController::class, 'getLowerAccounts'])->name('users.lowerAccounts');
Route::get('users/engineerOfficeAccounts', [\App\Http\Controllers\Api\UserController::class, 'getEngineerOfficeAccounts'])->name('users.engineerOfficeAccounts');
Route::get('users/contactorAccounts', [\App\Http\Controllers\Api\UserController::class, 'getContractorAccounts'])->name('users.contactorAccounts');
// ==================================================== materials ===================================================
Route::get('materials', [\App\Http\Controllers\Api\MaterialController::class, 'index'])->name('materials.index');


Route::post('nafath / login', [\App\Http\Controllers\NafathController::class, 'sendLoginRequest']);


Route::middleware('auth:sanctum')->get(' / user', function (Request $request) {
    return $request->user();
});
