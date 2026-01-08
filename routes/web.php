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

// Dashboard Route (Redirects based on role if accessed directly)
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'role:farmer'])->name('dashboard');
Route::get('/client-dashboard', [\App\Http\Controllers\ClientController::class, 'index'])->middleware(['auth', 'role:client'])->name('client.dashboard');

/* ======================================================================
   SHARED ROUTES (Accessible by both, actions restricted in controller/view)
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

    // Chat / Negotiation (Viewing)
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [\App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');

    // Market Viewing
    Route::get('/market', [\App\Http\Controllers\MarketController::class, 'index'])->name('market.index');
    Route::get('/market/{product}', [\App\Http\Controllers\MarketController::class, 'show'])->name('market.show');
    Route::get('/products/{product}', [\App\Http\Controllers\InventoryController::class, 'show'])->name('inventory.show');

    // Negotiation Actions (Shared)
    Route::post('/negotiation/{negotiation}/update', [\App\Http\Controllers\NegotiationController::class, 'update'])->name('negotiation.update');
    Route::post('/negotiation/{negotiation}/confirm', [\App\Http\Controllers\NegotiationController::class, 'confirm'])->name('negotiation.confirm');

});


/* ======================================================================
   FARMER ROUTES (Role: farmer)
   ====================================================================== */
Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->group(function () {
    
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
    Route::post('/crops/{id}/sell-to-market', [\App\Http\Controllers\CropController::class, 'sellToMarket'])->name('crops.sell-to-market');
    Route::get('/crops/{id}/activity-log', [\App\Http\Controllers\CropController::class, 'activityLog'])->name('crops.activity-log');

    // Tasks Management
    Route::resource('tasks', \App\Http\Controllers\TaskController::class);
    Route::post('/tasks/{task}/toggle', [\App\Http\Controllers\TaskController::class, 'toggleComplete'])->name('tasks.toggle');
    
    // Irrigation Management
    Route::get('/irrigations', [\App\Http\Controllers\IrrigationController::class, 'index'])->name('irrigations.index');
    
    // Inventory & Selling
    Route::resource('inventory', \App\Http\Controllers\InventoryController::class);
    Route::get('/inventory/{inventory}/sell', [\App\Http\Controllers\InventoryController::class, 'sellView'])->name('inventory.sell.view');
    Route::post('/inventory/{inventory}/sell', [\App\Http\Controllers\InventoryController::class, 'listOnMarket'])->name('inventory.sell.action');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');

    // Community (Is this farmer only? Assuming Shared for now, but keeping here if requested. Moving to shared if needed).
    // Let's keep Community shared? The user didn't specify. I'll put it in Shared for now, or duplicates.
    // Actually, usually community is for everyone. I'll move it to Shared.
});

/* ======================================================================
   CLIENT ROUTES (Role: client)
   ====================================================================== */
Route::middleware(['auth', 'role:client'])->prefix('client')->group(function () {
    
    // Market Actions (Buying / Generic)
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{item}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

    // Orders
    Route::resource('orders', \App\Http\Controllers\OrderController::class)->only(['index', 'show']);

    // Negotiation Initiation
    Route::get('/chat/start/{product}', [\App\Http\Controllers\ChatController::class, 'start'])->name('chat.start');
    Route::get('/chat/user/{otherUser}', [\App\Http\Controllers\ChatController::class, 'startWithUser'])->name('chat.user');
    
    // Reviews
    Route::post('/products/{product}/review', [\App\Http\Controllers\InventoryController::class, 'storeReview'])->name('product.review');
});

/* ======================================================================
   COMMUNITY ROUTES (Shared)
   ====================================================================== */
Route::middleware('auth')->group(function () {
    Route::get('/community/profile', [\App\Http\Controllers\CommunityController::class, 'myProfile'])->name('community.profile');
    Route::get('/community/{post}', [\App\Http\Controllers\CommunityController::class, 'show'])->name('community.show');
    Route::get('/community', [\App\Http\Controllers\CommunityController::class, 'index'])->name('community.index');
    Route::post('/community', [\App\Http\Controllers\CommunityController::class, 'store'])->name('community.store');
    Route::post('/community/{post}/comment', [\App\Http\Controllers\CommunityController::class, 'storeComment'])->name('community.comment');
    Route::post('/community/{post}/like', [\App\Http\Controllers\CommunityController::class, 'like'])->name('community.like');
    Route::post('/comments/{comment}/like', [\App\Http\Controllers\CommunityController::class, 'likeComment'])->name('comment.like');
});

