<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CropController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\IrrigationController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\MarketController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\NegotiationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ClientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Weather (No auth required as per previous implementation, but good to have)
Route::get('/weather', [DashboardController::class, 'index']); // Or dedicated endpoint if needed without auth

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/password', [SettingsController::class, 'updatePassword']);
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings/preferences', [SettingsController::class, 'updatePreferences']);
    Route::post('/settings/notifications/toggle', [SettingsController::class, 'toggleNotification']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Crop Management
    Route::apiResource('crops', CropController::class);
    Route::post('/crops/{crop}/irrigate', [CropController::class, 'irrigate']);
    Route::post('/crops/{crop}/harvest', [CropController::class, 'harvest']);
    // New Parity Routes
    Route::get('/crops/{crop}/growth-report', [CropController::class, 'growthReport']);
    Route::get('/crops/{crop}/activity-log', [CropController::class, 'activityLog']);
    Route::post('/crops/{crop}/sell-to-market', [CropController::class, 'sellToMarket']);

    // Irrigation History
    Route::get('/irrigations', [IrrigationController::class, 'index']);

    // Task Management
    Route::apiResource('tasks', TaskController::class);
    Route::post('/tasks/{task}/toggle', [TaskController::class, 'toggleComplete']);
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\Api\ReportController::class, 'index']);

    // Inventory Management
    Route::apiResource('inventory', InventoryController::class);
    Route::post('/inventory/{inventory}/sell', [InventoryController::class, 'listOnMarket']);

    // Market & Cart
    Route::get('/market', [MarketController::class, 'index']);
    Route::get('/market/{product}', [MarketController::class, 'show']);
    
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::delete('/cart/{item}', [CartController::class, 'remove']);
    Route::post('/cart/checkout', [CartController::class, 'checkout']);

    // Community (Social)
    Route::get('/community/profile', [CommunityController::class, 'myProfile']); // New Profile Route
    Route::get('/community/posts', [CommunityController::class, 'index']);
    Route::post('/community/posts', [CommunityController::class, 'store']);
    Route::get('/community/posts/{post}', [CommunityController::class, 'show']);
    Route::post('/community/posts/{post}/like', [CommunityController::class, 'like']);
    Route::post('/community/posts/{post}/comment', [CommunityController::class, 'storeComment']);

    // Chat
    Route::get('/chats', [ChatController::class, 'index']);
    Route::get('/chats/start/{product}', [ChatController::class, 'start']);
    Route::get('/chats/user/{otherUser}', [ChatController::class, 'startWithUser']);
    Route::get('/chats/{conversation}', [ChatController::class, 'show']);
    Route::post('/chats/{conversation}/send', [ChatController::class, 'send']);

    // Negotiation
    Route::post('/negotiation/{negotiation}/update', [NegotiationController::class, 'update']);
    Route::post('/negotiation/{negotiation}/confirm', [NegotiationController::class, 'confirm']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // Profile Completion
    Route::get('/complete-profile', [ProfileController::class, 'setup']);
    Route::post('/complete-profile', [ProfileController::class, 'complete']);

    // Client Dashboard
    Route::get('/client-dashboard', [ClientController::class, 'index']);

    // Product Reviews
    Route::post('/products/{product}/review', [InventoryController::class, 'storeReview']);
});

