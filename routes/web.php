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

// Logout → redirect to Create Ticket page
Route::post('/admin/logout', function () {
    session()->forget('admin_logged_in');
    return redirect()->route('tickets.create')->with('success', 'Logged out successfully.');
})->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', function () {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    $tickets = Ticket::all();
    return view('tickets.admin.dashboard', compact('tickets'));
})->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| Admin Ticket Actions
|--------------------------------------------------------------------------
*/

// Edit ticket form
Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');

// Update ticket (from form)
Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');

// Delete ticket
Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

// Mark as Paid
Route::post('/tickets/{ticket}/paid', [TicketController::class, 'markPaid'])->name('tickets.markPaid');

// Send Ticket via email
Route::post('/tickets/{ticket}/send', [TicketController::class, 'sendEmail'])->name('tickets.sendEmail');

/*
|--------------------------------------------------------------------------
| Public Routes (Ticket Create & View)
|--------------------------------------------------------------------------
*/

// Homepage → Ticket create form (public)
Route::get('/', function () {
    return app(TicketController::class)->create();
})->name('home');

// Ticket create form (public)
Route::get('/tickets/create', function () {
    return app(TicketController::class)->create();
})->name('tickets.create');

// Store ticket (public)
Route::post('/tickets', function (Request $request) {
    return app(TicketController::class)->store($request);
})->name('tickets.store');

// Download ticket PDF (Admin only)
Route::get('/tickets/{ticket}/download', function (Ticket $ticket) {
    if (!session('admin_logged_in')) {
        return redirect()->route('admin.login');
    }
    return app(TicketController::class)->downloadTicket($ticket);
})->name('tickets.download');

// Show ticket (public – view by user)
Route::get('/tickets/{ticket}', function (Ticket $ticket) {
    return app(TicketController::class)->show($ticket);
})->name('tickets.show');
