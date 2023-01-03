<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', function() {
        return redirect()->route('dashboard');
    })->name('default');
    
    // master
    Route::get('intitutions/ajax', 'IntitutionController@ajax')->name('intitutions.ajax');
    Route::resource('intitutions', 'IntitutionController');
    // Route::resource('users', 'UsersController');
    Route::prefix('users')->group(function() {
        Route::get('/{type}', 'UsersController@index')->name('users.index');
        Route::get('/{id}/edit/{type}', 'UsersController@edit')->name('users.edit');
        Route::get('/ajax/{type}', 'UsersController@ajax')->name('users.ajax');
        Route::post('/{id}/{type}', 'UsersController@update')->name('users.update');
        Route::delete('/{id}/{type}', 'UsersController@destroy')->name('users.destroy');
        Route::get('/{id}/{type}/show', 'UsersController@show')->name('users.show');
        Route::get('/create/{type}', 'UsersController@create')->name('users.create');
    });

    Route::group(['as' => 'expenses.'], function() {
        Route::prefix('expenses')->group(function() {
            Route::get('/category/ajax', 'ExpenseCategoryController@ajax')->name('category.ajax');
            Route::resource('/category', 'ExpenseCategoryController');
            Route::get('/method/ajax', 'ExpenseMethodController@ajax')->name('method.ajax');
            Route::resource('/method', 'ExpenseMethodController');
            Route::get('/type/ajax', 'ExpenseTypeController@ajax')->name('type.ajax');
            Route::resource('/type', 'ExpenseTypeController');
            Route::get('/main/ajax', 'ExpenseMainController@ajax')->name('main.ajax');
            Route::resource('/main', 'ExpenseMainController');
        });
    });

    Route::get('roles/ajax', 'RoleController@ajax')->name('roles.ajax');
    Route::resource('roles', 'RoleController');
    Route::get('permissions/ajax', 'PermissionController@ajax')->name('permissions.ajax');
    Route::resource('permissions', 'PermissionController');
    Route::get('positions/ajax', 'PositionController@ajax')->name('positions.ajax');
    Route::resource('positions', 'PositionController');
    Route::get('employees/ajax', 'EmployeesController@ajax')->name('employees.ajax');
    Route::resource('employees', 'EmployeesController');

    Route::post('/get-city', 'EmployeesController@getCity')->name('employees.get-city');
    Route::post('/get-district', 'EmployeesController@getDistrict')->name('employees.get-district');
    Route::post('/get-class', 'UsersController@getClass')->name('users.get-class');
    Route::post('/get-level', 'UsersController@getLevel')->name('users.get-level');
});
