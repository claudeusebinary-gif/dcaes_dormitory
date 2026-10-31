<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureTenant;
use App\Http\Middleware\NoStore;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\RoomRequestController;
use App\Http\Controllers\MarketplaceChatController;


// Public: landing page with login / sign-up panels 
Route::get('/', [AuthController::class, 'show'])->middleware(NoStore::class)->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated
Route::middleware(['auth', NoStore::class])->group(function () {

    // Generic landing spot (old dashboard.php) - bounces by role.
    Route::get('/dashboard', fn () => auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('tenant.home'));

    // Tenant area (old Pages/Home/*.php)
    Route::middleware(EnsureTenant::class)->prefix('home')->name('tenant.')->group(function () {
        Route::get('/', [TenantController::class, 'home'])->name('home');
        Route::get('/profile', [TenantController::class, 'profile'])->name('profile');
        Route::get('/dorm', [TenantController::class, 'dorm'])->name('dorm');
        Route::get('/rooms', [TenantController::class, 'rooms'])->name('rooms');
        Route::get('/payments', [TenantController::class, 'payments'])->name('payments');
        Route::post('/payments/pay', [TenantController::class, 'pay'])->middleware('throttle:10,1')->name('payments.pay');
        Route::get('/announcement', [TenantController::class, 'announcement'])->name('announcement');
        Route::get('/location', [TenantController::class, 'location'])->name('location');
        Route::get('/settings', [TenantController::class, 'settings'])->name('settings');
        Route::put('/settings/general', [TenantController::class, 'updateGeneral'])->name('settings.general');
        Route::put('/settings/billing-email', [TenantController::class, 'updateBillingEmail'])->name('settings.billing-email');
        Route::put('/settings/notifications', [TenantController::class, 'updateNotifications'])->name('settings.notifications');
        Route::put('/settings/password', [TenantController::class, 'updatePassword'])->middleware('throttle:5,1')->name('settings.password');
        Route::delete('/settings/sessions/{id}', [TenantController::class, 'logoutSession'])->where('id', '[A-Za-z0-9]+')->name('settings.sessions.destroy');
        Route::post('/settings/move-out', [RoomRequestController::class, 'moveOut'])->middleware('throttle:10,1')->name('requests.move-out');
        Route::get('/marketplace', [TenantController::class, 'marketplace'])->name('marketplace');
        Route::get('/marketplace/inbox', [MarketplaceChatController::class, 'inbox'])->name('marketplace.inbox');
        Route::get('/marketplace/chat', [MarketplaceChatController::class, 'thread'])->name('marketplace.chat');
        Route::post('/marketplace/chat', [MarketplaceChatController::class, 'send'])->middleware('throttle:30,1')->name('marketplace.chat.send');
        Route::post('/marketplace/items', [TenantController::class, 'marketplaceStore'])->middleware('throttle:10,1')->name('marketplace.store');
        Route::delete('/marketplace/items/{id}', [TenantController::class, 'marketplaceDestroy'])->whereNumber('id')->name('marketplace.destroy');
        Route::get('/calendar', [TenantController::class, 'calendar'])->name('calendar');
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');    
        Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');

        // Notification bell JSON API (old includes/notifications.php)
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->whereNumber('id')->name('notifications.read');
        Route::post('/messages', [TenantController::class, 'sendMessage'])->middleware('throttle:10,1')->name('messages.send');
        Route::post('/rooms/book', [TenantController::class, 'book'])->middleware('throttle:10,1')->name('rooms.book');
        Route::post('/requests/maintenance', [RoomRequestController::class, 'maintenance'])->middleware('throttle:10,1')->name('requests.maintenance');
        Route::post('/requests/transfer', [RoomRequestController::class, 'transfer'])->middleware('throttle:10,1')->name('requests.transfer');
    });

    // Admin area (old Pages/Admin/admin.php)
    Route::middleware(EnsureAdmin::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::view('/tenants',      'admin.page', ['title' => 'Tenants'])->name('tenants');
Route::view('/announcement', 'admin.page', ['title' => 'Announcement'])->name('announcement');
Route::view('/rooms',        'admin.page', ['title' => 'Rooms'])->name('rooms');
Route::view('/payments',     'admin.page', ['title' => 'Payments'])->name('payments');
Route::view('/complaints',   'admin.page', ['title' => 'Complaints'])->name('complaints');
Route::view('/approvals',    'admin.page', ['title' => 'Approvals'])->name('approvals');
Route::view('/settings',     'admin.page', ['title' => 'Settings'])->name('settings');
    });
});
