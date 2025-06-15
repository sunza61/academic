<?php

use App\Http\Controllers\AcademicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InformController;
use App\Http\Controllers\ResearchAreaController;
use App\Http\Controllers\ScopusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::get('/test',[AcademicController::class,'test'])->name('test');
Route::get('/academic-list',[AcademicController::class,'academicList'])->name('academic-list');
Route::get('/researcher',[AcademicController::class,'researcher'])->name('researcher');

Route::get('/research-area-list',[ResearchAreaController::class,'researchAreaList'])->name('research-area-list');
Route::get('/research-area-detail',[ResearchAreaController::class,'researchAreaDetail'])->name('research-area-detail');

Route::get('/scopus/search/{firstname}/{lastname}', [ScopusController::class, 'getAuthorByName']);
Route::get('/scopus/author/{authorId}', [ScopusController::class, 'getAuthorDetails']);
Route::get('/scopus/publications/{authorId}', [ScopusController::class, 'getPublications']);

Route::get('/scopus', [ScopusController::class, 'showAuthor']);






