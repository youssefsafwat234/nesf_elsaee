<?php
//
//use Illuminate\Support\Facades\Route;
//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider within a group which
//| contains the "web" middleware group. Now create something great!
//|
//*/
//
//require_once 'theme-routes.php';
//
//Route::get('/barebone', function () {
//    return view('barebone', ['title' => 'This is Title']);
//});
//
//require_once 'auth.php';
//
//
//// =============================== users , companies  , offices , lowers ,
//Route::get('/companies', [\App\Http\Controllers\Dashboard\UserController::class, 'getCompanyAccounts']);
//Route::get('/offices', [\App\Http\Controllers\Dashboard\UserController::class, 'getOfficeAccounts']);
//Route::get('/freelances', [\App\Http\Controllers\Dashboard\UserController::class, 'getFreelancerAccounts']);
//Route::get('/service-providers', [\App\Http\Controllers\Dashboard\UserController::class, 'getServiceProviderAccounts']);
//Route::get('/lowers', [\App\Http\Controllers\Dashboard\UserController::class, 'getLowerAccounts']);
//Route::get('/engineering-offices', [\App\Http\Controllers\Dashboard\UserController::class, 'getEngineeringOfficeAccounts']);
//Route::get('/contractors', [\App\Http\Controllers\Dashboard\UserController::class, 'getContractorAccounts']);
//Route::resource('/users', \App\Http\Controllers\Dashboard\UserController::class);

