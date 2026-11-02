<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureTenant;
use App\Http\Middleware\AuthOrGuest;
use App\Http\Middleware\NoStore;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\RoomRequestController;
use App\Http\Controllers\MarketplaceChatController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\ComplaintChatController;
use App\Http\Controllers\DirectChatController;


// Public: landing page with login / sign-up panels 
Route::get('/', [AuthController::class, 'show'])->middleware(NoStore::class)->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/guest', [AuthController::class, 'guest'])->name('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Legal pages (public)
Route::view('/terms',       'legal', ['page' => 'terms'])->name('legal.terms');
Route::view('/privacy',     'legal', ['page' => 'privacy'])->name('legal.privacy');
Route::view('/faqs',        'legal', ['page' => 'faqs'])->name('legal.faqs');
Route::view('/house-rules', 'legal', ['page' => 'rules'])->name('legal.rules');
// Authenticated
Route::middleware([AuthOrGuest::class, NoStore::class])->group(function () {

    // Generic landing spot (old dashboard.php) - bounces by role.
    Route::get('/dashboard', fn() => auth()->user()->isAdmin()
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
                Route::get('/dm/inbox', [DirectChatController::class, 'inbox'])->name('dm.inbox');
        Route::get('/dm/chat', [DirectChatController::class, 'thread'])->name('dm.chat');
        Route::post('/dm/chat', [DirectChatController::class, 'send'])->middleware('throttle:30,1')->name('dm.chat.send');
        Route::get('/complaints/inbox', [ComplaintChatController::class, 'inbox'])->name('complaints.inbox');
        Route::get('/complaints/chat', [ComplaintChatController::class, 'thread'])->name('complaints.chat');
        Route::post('/complaints/chat', [ComplaintChatController::class, 'send'])->middleware('throttle:30,1')->name('complaints.chat.send');
        Route::post('/marketplace/items', [TenantController::class, 'marketplaceStore'])->middleware('throttle:10,1')->name('marketplace.store');
        Route::delete('/marketplace/items/{id}', [TenantController::class, 'marketplaceDestroy'])->whereNumber('id')->name('marketplace.destroy');
        Route::get('/calendar', [TenantController::class, 'calendar'])->name('calendar');
        Route::post('/calendar/events', [TenantController::class, 'calendarStore'])->middleware('throttle:10,1')->name('calendar.store');
        Route::post('/calendar/events/{id}/rsvp', [TenantController::class, 'calendarRsvp'])->whereNumber('id')->middleware('throttle:30,1')->name('calendar.rsvp');
        Route::delete('/calendar/events/{id}', [TenantController::class, 'calendarDestroy'])->whereNumber('id')->name('calendar.destroy');
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
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [AdminNotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'read'])->whereNumber('id')->name('notifications.read');

      Route::get('/tenants',      [AdminController::class, 'tenants'])->name('tenants');
              Route::get('/tenants/{id}', [AdminController::class, 'tenantShow'])->whereNumber('id')->name('tenants.show');
Route::get('/announcement', [AdminController::class, 'announcements'])->name('announcement');
Route::get('/rooms',        [AdminController::class, 'rooms'])->name('rooms');
Route::post('/rooms',          [AdminController::class, 'roomStore'])->name('rooms.store');
Route::put('/rooms/{id}',      [AdminController::class, 'roomUpdate'])->whereNumber('id')->name('rooms.update');
Route::post('/rooms/{id}/maintenance', [AdminController::class, 'roomMaintenance'])->whereNumber('id')->name('rooms.maintenance');
Route::delete('/rooms/{id}',   [AdminController::class, 'roomDestroy'])->whereNumber('id')->name('rooms.destroy');
Route::get('/payments',     [AdminController::class, 'payments'])->name('payments');
Route::post('/payments',    [AdminController::class, 'paymentStore'])->name('payments.store');
Route::post('/payments/{id}/remind', [AdminController::class, 'paymentRemind'])->whereNumber('id')->middleware('throttle:30,1')->name('payments.remind');
Route::get('/complaints',   [AdminController::class, 'complaints'])->name('complaints');
Route::post('/complaints',  [AdminController::class, 'complaintStore'])->name('complaints.store');
Route::post('/complaints/{id}/status', [AdminController::class, 'complaintStatus'])->whereNumber('id')->name('complaints.status');
Route::get('/approvals',    [AdminController::class, 'approvals'])->name('approvals');
Route::get('/settings',     [AdminController::class, 'settings'])->name('settings');
        Route::post('/approvals/{id}/approve', [AdminController::class, 'approve'])->whereNumber('id')->name('approvals.approve');
        Route::post('/approvals/{id}/reject', [AdminController::class, 'reject'])->whereNumber('id')->name('approvals.reject');
                Route::post('/approvals', [AdminController::class, 'approvalStore'])->name('approvals.store');
        Route::post('/approvals/{id}/check', [AdminController::class, 'approvalCheck'])->whereNumber('id')->name('approvals.check');
        Route::post('/announcements', [AdminController::class, 'announcementStore'])->name('announcements.store');
        Route::put('/announcements/{id}', [AdminController::class, 'announcementUpdate'])->whereNumber('id')->name('announcements.update');
        Route::delete('/announcements/{id}', [AdminController::class, 'announcementDestroy'])->whereNumber('id')->name('announcements.destroy');
        Route::get('/complaints/inbox', [ComplaintChatController::class, 'adminInbox'])->name('complaints.inbox');
                Route::get('/dm/inbox', [DirectChatController::class, 'adminInbox'])->name('dm.inbox');
        Route::get('/dm/chat', [DirectChatController::class, 'thread'])->name('dm.chat');
        Route::post('/dm/chat', [DirectChatController::class, 'send'])->middleware('throttle:30,1')->name('dm.chat.send');
        Route::delete('/tenants/{id}', [AdminController::class, 'tenantDestroy'])->whereNumber('id')->name('tenants.destroy');
        Route::get('/complaints/chat', [ComplaintChatController::class, 'thread'])->name('complaints.chat');
        Route::post('/complaints/chat', [ComplaintChatController::class, 'send'])->middleware('throttle:30,1')->name('complaints.chat.send');
    });
});
