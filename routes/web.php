<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\News\Admin\CategoryController;
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

/**
 * Admin
 */
$groupData = [
    'prefix' => 'admin',
    'middleware' => ['auth', 'roles:admin,Chief-editor,Editor']
];
Route::group($groupData, function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('categories', CategoryController::class)
        ->names('admin.categories')->middleware(['auth', 'roles:admin,Chief-editor']);

});

Route::get('/', [IndexController::class, 'index'])->name('home');

