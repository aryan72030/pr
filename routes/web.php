<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployesController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingEmailController;
use App\Http\Controllers\SettingStripeController;
use App\Http\Controllers\StaffAvailabilityController;
use App\Http\Controllers\UserPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('employes',EmployesController::class);

    Route::resource('role',RoleController::class);

    Route::resource('blog',BlogController::class);

    Route::resource('service',ServiceController::class);


    Route::resource('staffAvailability',StaffAvailabilityController::class);

    Route::resource('appointment',AppointmentController::class);
 
    Route::resource('plan',PlanController::class);

    Route::get('user/plans', [UserPlanController::class, 'index'])->name('user.plans');
    Route::post('user/plan/subscribe/{planId}', [UserPlanController::class, 'subscribe'])->name('user.plan.subscribe');

    Route::resource('email',SettingEmailController::class);

    Route::resource('stripe',SettingStripeController::class);

});

require __DIR__.'/auth.php';
