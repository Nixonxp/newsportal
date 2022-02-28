<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\News\Admin\CategoryController;
use App\Http\Controllers\News\Admin\NewsController;
use App\Http\Controllers\Personal\PersonalController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\UserController;
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
]);

Route::get( '/logout', [ LoginController::class, 'logout' ] )->name('get-logout');
Route::get('/verify/{token}', [ RegisterController::class, 'verify' ] )->name('register.verify');

/**
 * Admin
 */
$groupData = [
    'prefix' => 'admin',
    'middleware' => ['auth', 'roles:admin,Chief-editor,Editor', 'throttle:60,1']
];
Route::group($groupData, function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('categories', CategoryController::class)
        ->names('admin.categories')->middleware(['auth', 'roles:admin,Chief-editor,Editor']);

    Route::resource('posts', NewsController::class)
        ->names('admin.posts')->middleware(['auth', 'roles:admin,Chief-editor,editor']);

    Route::resource('users', UserController::class)
        ->names('admin.users')->middleware(['auth', 'roles:admin']);

    Route::resource('ads', AdController::class)
        ->names('admin.ads')->middleware(['auth', 'roles:admin']);

    Route::get('/subscribes', [AdminController::class, 'subscribes'])->name('admin.subscribes.index');

    Route::get('/logs', [LogController::class, 'index'])->name('admin.logs.index');
    Route::get('/logs/{id}', [LogController::class, 'show'])->name('admin.logs.show');
    Route::delete('/logs/{id}', [LogController::class, 'destroy'])->name('admin.logs.destroy');
});

Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::post('/subscribe', [SubscribeController::class, 'subscribe'])->name('subscribe');
    Route::post('/unsubscribe', [SubscribeController::class, 'unsubscribe'])->name('unsubscribe');

    Route::get('/personal', [PersonalController::class, 'index'])->name('personal.index');
    Route::get('/personal/authors', [PersonalController::class, 'subscribeList'])->name('personal.authors');
});

Route::get('/category/{category:slug}', [IndexController::class, 'category'])->name('category');
Route::get('/category/{category:slug}/post/{post:slug}', [IndexController::class, 'newsPost'])->name('newspost');
Route::get('/search', [IndexController::class, 'search'])->name('search')->middleware('throttle:60,1');
Route::get('/', [IndexController::class, 'index'])->name('home');

