<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;

/*
|--------------------------------------------------------------------------
| Admin Login Routes
|--------------------------------------------------------------------------
*/

const ADMIN_EMAIL = "computerscience@gmail.com";
const ADMIN_PASSWORD = "12345678";

// Show login form
Route::get('/admin/login', function () {
    return view('tickets.admin.login'); // ✅ must exist at resources/views/tickets/admin/login.blade.php
})->name('admin.login');

// Handle login
Route::post('/admin/login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        session(['admin_logged_in' => true]);
        return redirect()->route('tickets.create')->with('success', 'Welcome Admin!');
    }

    return back()->withErrors([
        'login' => 'Invalid email or password.'
    ])->withInput();
})->name('admin.login.submit');

// Logout
Route::post('/admin/logout', function () {
    session()->forget('admin_logged_in');
    return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
})->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (manual session check on each route)
|--------------------------------------------------------------------------
*/

// Homepage → Ticket create form
Route::get('/', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->create();
})->name('home');

// Ticket create form
Route::get('/tickets/create', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->create();
})->name('tickets.create');

// Store ticket
Route::post('/tickets', function (Request $request) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->store($request);
})->name('tickets.store');

// Download ticket PDF
Route::get('/tickets/{ticket}/download', function (Ticket $ticket) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->downloadTicket($ticket);
})->name('tickets.download');

// Show ticket
Route::get('/tickets/{ticket}', function (Ticket $ticket) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->show($ticket);
})->name('tickets.show');
