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
    });
    
    // master
    Route::get('intitutions/ajax', 'IntitutionController@ajax')->name('intitutions.ajax');
    Route::resource('intitutions', 'IntitutionController');
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
});
