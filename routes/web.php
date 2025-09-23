<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;

const ADMIN_EMAIL = "computerscience@gmail.com";
const ADMIN_PASSWORD = "12345678";

/*
|--------------------------------------------------------------------------
| Admin Login Routes
|--------------------------------------------------------------------------
*/

// Show login form
Route::get('/admin/login', function () {
    return view('tickets.admin.login');
})->name('admin.login');

// Handle login
Route::post('/admin/login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
        session(['admin_logged_in' => true]);
        return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
    }

    return back()->withErrors([
        'login' => 'Invalid email or password.'
    ])->withInput();
})->name('admin.login.submit');

// Logout → balik sa create form
Route::post('/admin/logout', function () {
    session()->forget('admin_logged_in');
    return redirect()->route('home')->with('success', 'Logged out successfully.');
})->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Public Routes (no login required)
|--------------------------------------------------------------------------
*/

// Homepage → Ticket create form
Route::get('/', [TicketController::class, 'create'])->name('home');

// Store ticket (open for customers)
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

// Dashboard (with tickets)
Route::get('/admin/dashboard', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }

    $tickets = Ticket::all();
    return view('tickets.admin.dashboard', compact('tickets'));
})->name('admin.dashboard');

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

// Update ticket (inline edit sa dashboard)
Route::put('/tickets/{ticket}', function (Request $request, Ticket $ticket) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->update($request, $ticket);
})->name('tickets.update');

// Delete ticket
Route::delete('/tickets/{ticket}', function (Ticket $ticket) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->destroy($ticket);
})->name('tickets.destroy');

// Mark ticket as Paid
Route::post('/tickets/{ticket}/mark-paid', function (Ticket $ticket) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->markPaid($ticket);
})->name('tickets.markPaid');

// Send Ticket via Email
Route::post('/tickets/{ticket}/send-email', function (Ticket $ticket) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->sendEmail($ticket);
})->name('tickets.sendEmail');
