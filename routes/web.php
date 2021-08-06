<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InformController;
use Illuminate\Http\Request;
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
    //return view('welcome');
    return view('index');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'showpage'])->name('home');

Route::get('qq',function(Request $request){
    //return "1111";
    return $request->EQUIPMENT_ID;
    dd($request);
});

Route::get('assign', [App\Http\Controllers\HomeController::class, 'assign']);
Route::get('repairnoti', [App\Http\Controllers\HomeController::class, 'repairnoti']);
Route::get('manager', [App\Http\Controllers\HomeController::class, 'managerHome']);
Route::get('getjob', [App\Http\Controllers\HomeController::class, 'getjob']);
Route::get('inform', [InformController::class, 'inform']);

Route::get('test', [InformController::class, 'index'])->name('test');
Route::get('autocomplete', [InformController::class, 'autocomplete'])->name('autocomplete');
Route::get('getequipment', [InformController::class, 'getequipment'])->name('getequipment');
Route::post('addrepair_cer', [InformController::class, 'addrepair_cer'])->name('addrepair_cer');




