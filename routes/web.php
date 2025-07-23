<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => true, 'reset' => true, 'verify' => true]);
// إعادة تعريف مسار تسجيل الدخول ليستخدم الدالة المخصصة
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'checkrole:org_owner', 'institution.access'])->group(function () {
    Route::get('/organization/dashboard', [App\Http\Controllers\OrganizationController::class, 'dashboard'])->name('organization.dashboard');
    
    // Delegates Management
    Route::get('/organization/delegates', [App\Http\Controllers\OrganizationController::class, 'delegates'])->name('organization.delegates');
    Route::post('/organization/delegates', [App\Http\Controllers\OrganizationController::class, 'storeDelegate'])->name('organization.delegates.store');
    Route::get('/organization/delegates/{delegate}/edit', [App\Http\Controllers\OrganizationController::class, 'editDelegate'])->name('organization.delegates.edit');
    Route::put('/organization/delegates/{delegate}', [App\Http\Controllers\OrganizationController::class, 'updateDelegate'])->name('organization.delegates.update');
    Route::delete('/organization/delegates/{delegate}', [App\Http\Controllers\OrganizationController::class, 'destroyDelegate'])->name('organization.delegates.destroy');
    Route::post('/organization/delegates/{delegate}/toggle-status', [App\Http\Controllers\OrganizationController::class, 'toggleDelegateStatus'])->name('organization.delegates.toggleStatus');
    Route::post('/organization/delegates/{delegate}/assign', [App\Http\Controllers\OrganizationController::class, 'assignDelegate'])->name('organization.delegates.assign');
    Route::post('/organization/delegates/{delegate}/unassign', [App\Http\Controllers\OrganizationController::class, 'unassignDelegate'])->name('organization.delegates.unassign');
    
    // Drivers Management (New System)
    Route::get('/organization/drivers/management', [App\Http\Controllers\OrganizationController::class, 'driversManagement'])->name('organization.drivers.management');
    Route::post('/organization/drivers/{driver}/assign', [App\Http\Controllers\OrganizationController::class, 'assignDriver'])->name('organization.drivers.assign');
    Route::post('/organization/drivers/{driver}/unassign', [App\Http\Controllers\OrganizationController::class, 'unassignDriver'])->name('organization.drivers.unassign');
    Route::post('/organization/drivers/{driver}/assign-truck', [App\Http\Controllers\OrganizationController::class, 'assignTruckToDriver'])->name('organization.drivers.assignTruck');
    
    // Trucks Management
    Route::get('/organization/trucks', [App\Http\Controllers\OrganizationController::class, 'trucks'])->name('organization.trucks');
    Route::post('/organization/trucks', [App\Http\Controllers\OrganizationController::class, 'storeTruck'])->name('organization.trucks.store');
    Route::get('/organization/trucks/{truck}/edit', [App\Http\Controllers\OrganizationController::class, 'editTruck'])->name('organization.trucks.edit');
    Route::put('/organization/trucks/{truck}', [App\Http\Controllers\OrganizationController::class, 'updateTruck'])->name('organization.trucks.update');
    Route::delete('/organization/trucks/{truck}', [App\Http\Controllers\OrganizationController::class, 'destroyTruck'])->name('organization.trucks.destroy');
    Route::post('/organization/trucks/{truck}/unassign', [App\Http\Controllers\OrganizationController::class, 'unassignTruck'])->name('organization.trucks.unassign');
    Route::post('/organization/trucks/{truck}/assign-driver', [App\Http\Controllers\OrganizationController::class, 'assignDriverToTruck'])->name('organization.trucks.assignDriver');
    
    // Stations Management
    Route::get('/organization/stations', [App\Http\Controllers\OrganizationController::class, 'stations'])->name('organization.stations');
    Route::post('/organization/stations', [App\Http\Controllers\OrganizationController::class, 'storeStation'])->name('organization.stations.store');
    Route::match(['get','post'], '/organization/stations/create', [App\Http\Controllers\OrganizationController::class, 'createStation'])->name('organization.stations.create');
    Route::get('/organization/stations/{station}/edit', [App\Http\Controllers\OrganizationController::class, 'editStation'])->name('organization.stations.edit');
    Route::put('/organization/stations/{station}', [App\Http\Controllers\OrganizationController::class, 'updateStation'])->name('organization.stations.update');
    Route::delete('/organization/stations/{station}', [App\Http\Controllers\OrganizationController::class, 'destroyStation'])->name('organization.stations.destroy');
    
    // Other Organization Routes
    Route::get('/organization/orders', [App\Http\Controllers\OrganizationController::class, 'orders'])->name('organization.orders');
    Route::get('/organization/beneficiaries', [App\Http\Controllers\OrganizationController::class, 'beneficiaries'])->name('organization.beneficiaries');
    Route::get('/organization/reports', [App\Http\Controllers\OrganizationController::class, 'reports'])->name('organization.reports');
    Route::get('/organization/settings', [App\Http\Controllers\OrganizationController::class, 'settings'])->name('organization.settings');
    Route::get('/organization/users', [App\Http\Controllers\OrganizationController::class, 'users'])->name('organization.users');
    Route::get('/organization/statistics', [App\Http\Controllers\OrganizationController::class, 'statistics'])->name('organization.statistics');
    
    // Water Requests Management
    Route::get('/organization/water-requests', [App\Http\Controllers\OrganizationController::class, 'waterRequests'])->name('organization.water-requests');
    Route::get('/organization/water-requests/{id}', [App\Http\Controllers\OrganizationController::class, 'showWaterRequest'])->name('organization.water-requests.show');
    Route::post('/organization/water-requests/{request}/assign-truck', [App\Http\Controllers\OrganizationController::class, 'assignTruckToRequest'])->name('organization.water-requests.assignTruck');
});

Route::middleware(['auth', 'checkrole:representative'])->group(function () {
    Route::get('/delegate/dashboard', [App\Http\Controllers\DelegateController::class, 'dashboard'])->name('delegate.dashboard');
    
    // Water Requests Management
    Route::get('/delegate/water-requests', [App\Http\Controllers\WaterRequestController::class, 'index'])->name('delegate.water-requests');
    Route::get('/delegate/water-requests/create', [App\Http\Controllers\WaterRequestController::class, 'create'])->name('delegate.water-requests.create');
    Route::post('/delegate/water-requests', [App\Http\Controllers\WaterRequestController::class, 'store'])->name('delegate.water-requests.store');
    Route::get('/delegate/water-requests/{waterRequest}', [App\Http\Controllers\WaterRequestController::class, 'show'])->name('delegate.water-requests.show');
    Route::patch('/delegate/water-requests/{waterRequest}/status', [App\Http\Controllers\WaterRequestController::class, 'updateStatus'])->name('delegate.water-requests.update-status');
    Route::delete('/delegate/water-requests/{waterRequest}', [App\Http\Controllers\WaterRequestController::class, 'destroy'])->name('delegate.water-requests.destroy');
});

Route::middleware(['auth', 'checkrole:driver'])->group(function () {
    Route::get('/driver/dashboard', [App\Http\Controllers\DriverController::class, 'dashboard'])->name('driver.dashboard');
    Route::get('/driver/delivery-tasks', [App\Http\Controllers\DriverController::class, 'deliveryTasks'])->name('driver.delivery_tasks');
    Route::post('/driver/tasks/{task}/start', [App\Http\Controllers\DriverController::class, 'startTask'])->name('driver.tasks.start');
    Route::post('/driver/tasks/{task}/complete', [App\Http\Controllers\DriverController::class, 'completeTask'])->name('driver.tasks.complete');
});

// إعادة تعيين كلمة المرور عبر الجوال
Route::post('password/sms/send', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetCode'])->name('password.sms.send');
Route::get('password/sms/verify', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyCodeForm'])->name('password.sms.verify');
Route::post('password/sms/verify', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyCode']);
Route::post('password/sms/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('password.sms.reset');

// إعادة تعيين كلمة المرور عبر البريد بكود تحقق
Route::post('password/email/code', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendEmailCode'])->name('password.email.code');
Route::get('password/email/verify', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showEmailVerifyForm'])->name('password.email.verify');
Route::post('password/email/verify', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyEmailCode']);
Route::get('password/email/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showEmailResetForm'])->name('password.email.reset');
Route::post('password/email/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetEmailPassword'])->name('password.email.reset.submit');
