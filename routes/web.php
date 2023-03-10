<?php

namespace yayasan\routes;

use App\Http\Controllers\ProfileController;
use App\Models\Intitution;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Vinkla\Hashids\Facades\Hashids;

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

// get institution list from redis
$institutions = Redis::get('institutions');
$institutions = json_decode($institutions, true);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Localization
Route::get('/js/lang.js', function () {
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');

        $files = glob(resource_path('lang/'.$lang.'/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });

    header('Content-Type: text/javascript');
    echo 'window.i18n = '.json_encode($strings).';';
    exit();
})->name('assets.lang');

Route::middleware('auth')->group(function () use ($institutions) {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('default');

    // begin::master
    Route::get('intitutions/ajax', 'IntitutionController@ajax')->name('intitutions.ajax');
    Route::post('/intitutions/delete-class', 'IntitutionController@deleteClass')->name('intitutions.delete-class');
    Route::post('/intitutions/delete-level', 'IntitutionController@deleteLevel')->name('intitutions.delete-level');
    Route::post('/intitutions/show-class-level-form', 'IntitutionController@generateClassLevelForm')->name('intitutions.show-class-level-form');
    Route::post('/intitutions/detail-data/intitutions', 'IntitutionController@detailDataInstitution')->name('intitutions.detail-data.institution');
    Route::post('/intitutions/store-homeroom', 'IntitutionController@storeHomeroom')->name('intitutions.store-homeroom');
    Route::get('/intitutions/show-homeroom-teacher', 'IntitutionController@showHomeroomTeacher')->name('intitutions.show-homeroom-teacher');
    Route::resource('intitutions', 'IntitutionController');
    // Route::resource('users', 'UsersController');
    Route::prefix('users')->group(function () {
        Route::get('/users/internal', 'UsersController@index')->name('users.index.internal');
        Route::get('/users/external', 'UsersController@index')->name('users.index.external');
        Route::get('/{id}/edit/{type}', 'UsersController@edit')->name('users.edit');
        Route::get('/ajax/{type}', 'UsersController@ajax')->name('users.ajax');
        Route::post('/{id}/{type}', 'UsersController@update')->name('users.update');
        Route::delete('/{id}/{type}', 'UsersController@destroy')->name('users.destroy');
        Route::get('/{id}/{type}/show', 'UsersController@show')->name('users.show');
        Route::get('/create/{type}', 'UsersController@create')->name('users.create');
    });

    Route::group(['as' => 'expenses.'], function () {
        Route::prefix('expenses')->group(function () {
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

    Route::group(['as' => 'income.'], function () {
        Route::prefix('income')->group(function () {
            Route::get('/category/ajax', 'IncomeCategoryController@ajax')->name('category.ajax');
            Route::resource('/category', 'IncomeCategoryController');
            Route::get('/type/ajax', 'IncomeTypeController@ajax')->name('type.ajax');
            Route::resource('/type', 'IncomeTypeController');
            Route::get('/method/ajax', 'IncomeMethodController@ajax')->name('method.ajax');
            Route::resource('method', 'IncomeMethodController');
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
    // end::master

    // begin::incomes
    Route::get('incomes/ajax', 'IncomeController@ajax')->name('incomes.ajax');
    Route::post('/incomes/get-detail-user', 'IncomeController@getDetailuser')->name('invoices.get-detail-user');
    Route::post('/incomes/check-invoice-number', 'IncomeController@checkInvoiceNumber')->name('incomes.check-invoice-number');
    Route::post('/incomes/build-item-row', 'IncomeController@buildItemRow')->name('incomes.build-item-row');
    Route::post('/incomes/upload/attachement', 'IncomeController@uploadAttachment')->name('upload.attachment');
    Route::delete('/incomes/upload/attachement', 'IncomeController@deleteAttachment')->name('upload.attachment');
    Route::post('/incomes/upload/proof-of-payment', 'IncomeController@uploadProof')->name('upload.proof');
    Route::delete('/incomes/upload/proof-of-payment', 'IncomeController@deleteProof')->name('delete.proof');
    Route::post('/incomes/append-payment-detail', 'IncomeController@appendPaymentDetail');
    Route::post('/incomes/pay', 'IncomeController@pay')->name('incomes.pay');
    Route::post('/incomes/pay-non-period', 'IncomeController@payNonPeriod')->name('incomes.pay-non-period');
    Route::post('/incomes/generate-transaction', 'IncomeController@generateTransaction')->name('incomes.generate-transaction');
    Route::post('/incomes/proof-of-payment', 'IncomeController@proofOfPayment')->name('incomes.proof-of-payment');
    Route::post('/incomes/generate-data', 'IncomeController@generateData')->name('incomes.generate-data');
    if ($institutions) {
        if (count($institutions) > 0) {
            foreach ($institutions as $i) {
                Route::get('/incomes/' . Hashids::encode($i['id']), 'IncomeController@index')->name('incomes.index.' . $i['id']);
            }
        }
    }
    Route::get('incomes/{id}', 'IncomeController@show')->name('incomes.show');

    // Override create route
    Route::post('/incomes/invoice/monthly/form', 'IncomeController@create')->name('incomes.create');

    Route::post('/incomes/change/monthly-income/by-level', 'IncomeController@changeMonthlyIncomceByLevel')->name('incomes.change.monthly-income.by-level');
    Route::post('/incomes/invoice-non-period', 'IncomeController@invoiceNonPeriod')->name('incomes.invoice-non-period');
    Route::post('/incomes/filter', 'IncomeController@filterIncome')->name('incomes.filter');
    Route::get('/incomes/data/ajax', 'IncomeController@datatable')->name('incomes.data.ajax');

    // Route::resource('incomes', 'IncomeController');
    // end::incomes

    Route::get('/user/update-saldo', 'UsersController@updateSaldo')->name('users.update.saldo');
    Route::get('/users/wallet/ajax', 'UsersController@walletAjax')->name('users.wallet.ajax');
    Route::get('/users/wallet/detail/{user_id}', 'UsersController@detailWallet')->name('users.detail.saldo');
    Route::post('/users/wallet/form/send', 'UsersController@formSendWallet')->name('users.wallet.send-form');
    Route::post('/users/wallet/form-transfer-fund/send', 'UsersController@formTransferFund')->name('users.wallet.transfer-fund-form');
    Route::post('/users/wallet/send/global', 'UsersController@sendWallet')->name('users.wallet.send');
    Route::post('/users/upload/attachement/send-wallet', 'UsersController@uploadAttachment')->name('users.upload.attachment-send-wallet');
    Route::delete('/users/upload/attachement/send-wallet', 'UsersController@deleteAttachment')->name('users.upload.attachment-send-wallet');


    // begin::proposal
    Route::post('/proposals/upload-attachments', 'ProposalController@uploadAttachments')->name('proposals.update-attachments');
    Route::delete('/proposals/upload-attachments', 'ProposalController@deleteAttachments')->name('proposals.update-attachments');
    Route::get('/proposals/ajax', 'ProposalController@ajax')->name('proposals.ajax');
    Route::post('/proposals/{id}/update', 'ProposalController@update')->name('proposals.update-form');
    Route::get('/proposals/download/{document_id}', 'ProposalController@download')->name('proposals.download.document');
    Route::get('/proposals/approve/{id}', 'ProposalController@approve')->name('proposals.approve');
    Route::get('/proposals/publish/{id}', 'ProposalController@publish')->name('proposals.publish');
    Route::post('/proposals/funding/{id}', 'ProposalController@funding')->name('proposals.funding');
    Route::resource('proposals', 'ProposalController');
    // end::proposal
});
