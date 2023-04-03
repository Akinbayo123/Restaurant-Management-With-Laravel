<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Frontend\MenuController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Admin\MenuController as admin;
use App\Http\Controllers\Frontend\ReservationController;
use App\Http\Controllers\Admin\ReservationController as res_admin;
use App\Http\Controllers\Admin\CategoryController as admin_category;

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
Route::get('/', [WelcomeController::class, 'index']);

Route::get('/redirect', [AdminController::class, 'redirect'])->middleware('auth');
Route::get('/admin/categories', [admin_category::class, 'category'])->middleware('auth','admin')->name('category');
Route::get('/admin/menus', [admin::class, 'menu'])->middleware('auth','admin')->name('menu');
Route::get('/admin/tables', [TableController::class, 'table'])->middleware('auth','admin')->name('table');
Route::get('/admin/reservations', [res_admin::class, 'reservation'])->middleware('auth','admin')->name('reservation');
Route::get('/admin/categories/create', [admin_category::class, 'create'])->middleware('admin');
Route::get('/admin/menu/create', [admin::class, 'create'])->middleware('admin');
Route::get('/admin/table/create', [TableController::class, 'create'])->middleware('admin')->name('table_view');
Route::get('/admin/reservation/create', [res_admin::class, 'create'])->middleware('admin')->name('reservation_create');
Route::post('/category/store', [admin_category::class, 'store']);
Route::post('/menu/store', [admin::class, 'store']);
Route::delete('/category/delete/{id}', [admin_category::class, 'destroy']);
Route::get('/category/{category}/edit', [admin_category::class, 'edit'])->name('edit_category');
Route::get('/menu/{menu}/edit', [admin::class, 'edit'])->name('edit_menu');
Route::put('/category/{category}/update', [admin_category::class, 'update'])->name('update_category');
Route::put('/menu/{menu}/update', [admin::class, 'update'])->name('update_menu');
Route::delete('/menu/delete/{menu}', [admin::class, 'destroy']);
Route::post('/table/store', [TableController::class, 'store'])->name('store_table');
Route::get('/table/edit/{table}', [TableController::class, 'edit'])->name('tables_edit');
Route::delete('/table/delete/{table}', [TableController::class, 'destroy'])->name('tables_delete');
Route::put('/table/update/{table}', [TableController::class, 'update']);
Route::post('/reservation/store', [res_admin::class, 'store'])->name('store_reservation');
Route::delete('/reservation/delete/{res}', [res_admin::class, 'destroy'])->name('res.delete');
Route::get('/reservation/edit/{reservation}', [res_admin::class, 'edit'])->name('res.edit');
Route::put('/reservation/update/{reservation}', [res_admin::class, 'update'])->name('admin.reservations.update');
//Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    //Route::get('/', [AdminController::class, 'index'])->name('index');
//});

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
Route::get('/reservation/step-one', [ReservationController::class, 'stepOne'])->name('reservations.step.one');
Route::post('/reservation/step-one', [ReservationController::class, 'storeStepOne'])->name('reservations.store.step.one');
Route::get('/reservation/step-two', [ReservationController::class, 'stepTwo'])->name('reservations.step.two');
Route::post('/reservation/step-two', [ReservationController::class, 'storeStepTwo'])->name('reservations.store.step.two');
Route::get('/reservation/thankyou', [WelcomeController::class, 'thankyou'])->name('thankyou');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');





require __DIR__.'/auth.php';
