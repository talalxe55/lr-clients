<?php

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

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\ServicesController;
            

Route::get('/', function () {return redirect('/clients');})->middleware('guest');
//Route::get('/home', function () {return redirect('/dashboard');})->middleware('guest');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('add-user', [RegisterController::class, 'create'])->middleware('auth')->name('add-user')->middleware('role:superadmin|admin');
Route::post('sign-up', [RegisterController::class, 'store'])->middleware('auth')->name('signup')->middleware('role:superadmin|admin');
Route::get('sign-in', [SessionsController::class, 'create'])->middleware('guest')->name('login');
Route::post('sign-in', [SessionsController::class, 'store'])->middleware('guest');
Route::post('verify', [SessionsController::class, 'show'])->middleware('guest');
Route::post('reset-password', [SessionsController::class, 'update'])->middleware('guest')->name('password.update');
Route::get('verify', function () {
	return view('sessions.password.verify');
})->middleware('guest')->name('verify'); 
Route::get('/reset-password/{token}', function ($token) {
	return view('sessions.password.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('sign-out', [SessionsController::class, 'destroy'])->middleware('auth')->name('logout');
Route::get('profile', [ProfileController::class, 'create'])->middleware('auth')->name('profile');
Route::post('user-profile', [ProfileController::class, 'update'])->middleware('auth');
Route::group(['middleware' => 'auth'], function () {
	// Route::get('billing', function () {
	// 	return view('pages.billing');
	// })->name('billing');
	// Route::get('tables', function () {
	// 	return view('pages.tables');
	// })->name('tables');
	// Route::get('rtl', function () {
	// 	return view('pages.rtl');
	// })->name('rtl');
	// Route::get('virtual-reality', function () {
	// 	return view('pages.virtual-reality');
	// })->name('virtual-reality');
	// Route::get('notifications', function () {
	// 	return view('pages.notifications');
	// })->name('notifications');
	// Route::get('static-sign-in', function () {
	// 	return view('pages.static-sign-in');
	// })->name('static-sign-in');
	// Route::get('static-sign-up', function () {
	// 	return view('pages.static-sign-up');
	// })->name('static-sign-up');
	// Route::get('user-management', function () {
	// 	return view('pages.user.user-management');
	// })->name('user-management');

	Route::get('user-management', [UsersController::class, 'index'])->name('user-management')->middleware('role:superadmin|admin');
	Route::get('edit-user/{userid}', [UsersController::class, 'showUser'])->name('edit.user')->middleware('role:superadmin|admin');
	Route::post('edit-user/{userid}', [UsersController::class, 'editUser'])->name('update.user')->middleware('role:superadmin|admin');
	Route::post('delete-user/{userid}', [UsersController::class, 'deleteUser'])->name('delete.user')->middleware('role:superadmin|admin');
	Route::get('user-profile', function () {
		return view('pages.user.user-profile');
	})->name('user-profile');
	Route::match(array('GET','POST'),'clients', [ClientController::class, 'index'])->name('clients')->middleware('role:superadmin|admin|user','permission:view clients');
	Route::post('search-clients', [ClientController::class, 'searchClients'])->name('search.clients')->middleware('role:superadmin|admin|user','permission:view clients');
	Route::get('add-client', [ClientController::class, 'addClient'])->name('add.client')->middleware('role:superadmin|admin|user','permission:add client');
	Route::post('add-client', [ClientController::class, 'createClient'])->name('create.client')->middleware('role:superadmin|admin|user','permission:add client');
	Route::get('edit-client/{clientid}', [ClientController::class, 'showClient'])->name('edit.client')->middleware('role:superadmin|admin|user','permission:edit client');
	Route::post('edit-client/{clientid}', [ClientController::class, 'updateClient'])->name('update.client')->middleware('role:superadmin|admin|user','permission:update client');
	Route::post('delete-client/{clientid}', [ClientController::class, 'deleteClient'])->name('delete.client')->middleware('role:superadmin|admin|user','permission:delete client');
	Route::get('delete-client-request/{clientid}', [ClientController::class, 'deleteClientrequest'])->name('delete.client.request')->middleware('role:superadmin|admin|user');
	Route::get('delete-client-service/{clientid}/{serviceid}', [ClientController::class, 'deleteClientservice'])->name('delete.client.service')->middleware('role:superadmin|admin|user','permission:delete client service');
	Route::post('add-client-service/{clientid}', [ClientController::class, 'addClientservice'])->name('add.client.service')->middleware('role:superadmin|admin|user','permission:add client service');
	Route::post('edit-client-service/{clientid}/{serviceid}', [ClientController::class, 'editClientservice'])->name('edit.client.service')->middleware('role:superadmin|admin|user','permission:edit client service');
	Route::get('client-billing/{clientid}/billing/{billingid}', [BillingController::class, 'editClientBilling'])->name('edit.client.billing')->middleware('role:superadmin|admin|user','permission:generate client billing');
	// Route::get('add-user', function () {
	// 	return view('pages.user.add-user');
	// })->name('add-user');
});

Route::group(['prefix' => 'invoices' , 'middleware' => 'auth'], function () {
	Route::match(array('GET','POST'),'', [InvoicesController::class, 'index'])->name('invoices.all')->middleware('role:superadmin|admin','permission:view invoices');
	Route::post('create/{clientid}/billing/{billingid}', [InvoicesController::class, 'createInvoice'])->name('create.invoice')->middleware('role:superadmin|admin','permission:add invoice|generate client billing');
	Route::post('delete/{invoiceid}/{clientid}', [InvoicesController::class, 'deleteInvoice'])->name('delete.invoice')->middleware('role:superadmin|admin','permission:delete invoice');
	Route::get('{invoiceid}', [InvoicesController::class, 'viewInvoice'])->name('view.invoice')->middleware('role:superadmin|admin','permission:edit invoice');
});

Route::group(['prefix' => 'services' , 'middleware' => 'auth'], function () {
	Route::get('', [ServicesController::class, 'index'])->name('services.all')->middleware('role:superadmin|admin','permission:view services');
	Route::get('add-service', [ServicesController::class, 'addService'])->name('add.service')->middleware('role:superadmin|admin','permission:add service');
	Route::post('create', [ServicesController::class, 'createService'])->name('create.service')->middleware('role:superadmin|admin','permission:add service');
	Route::post('delete/{serviceid}', [ServicesController::class, 'deleteService'])->name('delete.service')->middleware('role:superadmin|admin','permission:delete service');
	Route::get('{serviceid}', [ServicesController::class, 'viewService'])->name('view.service')->middleware('role:superadmin|admin','permission:edit service');
	Route::post('{serviceid}', [ServicesController::class, 'updateService'])->name('update.service')->middleware('role:superadmin|admin','permission:edit service');
});

Route::group(['prefix' => 'logs' , 'middleware' => 'auth'], function () {
	Route::get('', [LogsController::class, 'index'])->name('logs.all')->middleware('role:superadmin|admin','permission:view logs');
	// Route::post('create/{clientid}/billing/{billingid}', [InvoicesController::class, 'createInvoice'])->name('create.invoice')->middleware('role:superadmin|admin','permission:add invoice|generate client billing');
	// Route::post('delete/{invoiceid}/{clientid}', [InvoicesController::class, 'deleteInvoice'])->name('delete.invoice')->middleware('role:superadmin|admin','permission:delete invoice');
	// Route::get('{invoiceid}', [InvoicesController::class, 'viewInvoice'])->name('view.invoice')->middleware('role:superadmin|admin','permission:edit invoice');
});