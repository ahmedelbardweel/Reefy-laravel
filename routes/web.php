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


// Language Switch
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Onboarding
Route::get('/', [\App\Http\Controllers\OnboardingController::class, 'index'])->name('welcome');

// Auth Routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'index'])->name('login');
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Profile Completion Routes
Route::get('/complete-profile', [\App\Http\Controllers\ProfileController::class, 'setup'])->middleware('auth')->name('profile.setup');
Route::post('/complete-profile', [\App\Http\Controllers\ProfileController::class, 'complete'])->middleware('auth')->name('profile.complete');

// Dashboard Route
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');


/* ======================================================================
   SHARED ROUTES (Accessible by all authenticated users)
   ====================================================================== */
Route::middleware('auth')->group(function () {
    // Settings & Profile
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/profile/edit', [\App\Http\Controllers\SettingsController::class, 'editProfile'])->name('settings.profile.edit');
    Route::put('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::get('/settings/password/edit', [\App\Http\Controllers\SettingsController::class, 'editPassword'])->name('settings.password.edit');
    Route::put('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/settings/preferences', [\App\Http\Controllers\SettingsController::class, 'updatePreferences'])->name('settings.preferences.update');
    Route::post('/settings/notifications/toggle', [\App\Http\Controllers\SettingsController::class, 'toggleNotification'])->name('settings.notifications.toggle');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Community Routes
    Route::prefix('community')->group(function () {
        Route::get('/', [\App\Http\Controllers\CommunityController::class, 'index'])->name('community.index');
        Route::post('/', [\App\Http\Controllers\CommunityController::class, 'store'])->name('community.store');
        Route::get('/profile', [\App\Http\Controllers\CommunityController::class, 'myProfile'])->name('community.profile');
        Route::get('/{post}', [\App\Http\Controllers\CommunityController::class, 'show'])->name('community.show');
        Route::post('/{post}/like', [\App\Http\Controllers\CommunityController::class, 'like'])->name('community.like');
        Route::post('/{post}/comment', [\App\Http\Controllers\CommunityController::class, 'storeComment'])->name('community.comment.store');
        Route::post('/comments/{comment}/like', [\App\Http\Controllers\CommunityController::class, 'likeComment'])->name('community.comment.like');
    });
});


/* ======================================================================
   FARMER / ADMIN ROUTES (Management Features)
   ====================================================================== */
// For now, we allow any auth user to access "farmer" features or we can restrict if needed.
// Given "Remove everything related to client", we assume everyone can farm?
// Or we keep 'role:farmer' but everyone IS a farmer now. I'll remove the strict role check for simpler transition, or set default role to farmer.
Route::middleware(['auth'])->prefix('farmer')->group(function () {
    
    // Crops Management
    Route::get('/crops', [\App\Http\Controllers\CropController::class, 'index'])->name('crops.index');
    Route::get('/crops/create', [\App\Http\Controllers\CropController::class, 'create'])->name('crops.create');
    Route::post('/crops', [\App\Http\Controllers\CropController::class, 'store'])->name('crops.store');
    Route::get('/crops/{crop}', [\App\Http\Controllers\CropController::class, 'show'])->name('crops.show');
    Route::get('/crops/{crop}/edit', [\App\Http\Controllers\CropController::class, 'edit'])->name('crops.edit');
    Route::put('/crops/{id}', [\App\Http\Controllers\CropController::class, 'update'])->name('crops.update');
    Route::delete('/crops/{id}', [\App\Http\Controllers\CropController::class, 'destroy'])->name('crops.destroy');
    
    // Crop Actions
    Route::post('/crops/{id}/irrigate', [\App\Http\Controllers\CropController::class, 'irrigate'])->name('crops.irrigate');
    Route::post('/crops/{id}/harvest', [\App\Http\Controllers\CropController::class, 'harvest'])->name('crops.harvest');
    Route::get('/crops/{id}/growth-report', [\App\Http\Controllers\CropController::class, 'growthReport'])->name('crops.growth-report');
    Route::get('/crops/{id}/activity-log', [\App\Http\Controllers\CropController::class, 'activityLog'])->name('crops.activity-log');

    // Tasks Management
    Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    Route::post('/tasks/{task}/toggle', [\App\Http\Controllers\TaskController::class, 'toggleComplete'])->name('tasks.toggle');
    
    // Irrigation Management
    Route::get('/irrigations', [\App\Http\Controllers\IrrigationController::class, 'index'])->name('irrigations.index');
    
    // Inventory
    Route::resource('inventory', \App\Http\Controllers\InventoryController::class);

    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
});

/* ======================================================================
   ADMIN ROUTES
   ====================================================================== */
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/advice', [\App\Http\Controllers\AdminController::class, 'storeAdvice'])->name('admin.advice.store');
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('admin.users.delete');
});

