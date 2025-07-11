<?php

use App\Http\Controllers\ScraperController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

require __DIR__ . '/auth.php';



Route::get('/', [ScraperController::class, 'index'])->name('liturgy.index');
Route::get('/{date}', [ScraperController::class, 'show'])->name('liturgy.show');
