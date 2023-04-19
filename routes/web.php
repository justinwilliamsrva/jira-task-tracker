<?php

use App\Http\Livewire\Input;
use App\Http\Livewire\Output;
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
// Route::domain('jira-task-tracker.justinw-projects.com')->group(function () {
//     Route::get('/', Input::class)->name('input');
//     Route::get('/output', Output::class)->name('output');
// });
Route::get('/', Input::class)->name('input');
Route::get('/output', Output::class)->name('output');
