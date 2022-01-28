<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
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

Auth::routes([
    'reset' => false,
    'confirm' => false,
    'verify' => false
]);

Route::get( '/logout', [ LoginController::class, 'logout' ] )->name('get-logout');

Route::middleware('roles:admin,Chief-editor,Editor')->group(function(){
    Route::get('/admin', [HomeController::class, 'index'])->name('admin');
});

Route::get('/', [IndexController::class, 'index'])->name('home');

