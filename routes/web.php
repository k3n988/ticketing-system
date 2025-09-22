<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TicketController;
use App\Models\Ticket;

/*
|--------------------------------------------------------------------------
| Admin Credentials
|--------------------------------------------------------------------------
*/
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

    return back()->withErrors(['login' => 'Invalid email or password.'])->withInput();
})->name('admin.login.submit');

// Logout
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
Route::middleware(['web'])->group(function () {
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('/tickets/{ticket}/paid', [TicketController::class, 'markPaid'])->name('tickets.markPaid');
    Route::post('/tickets/{ticket}/send', [TicketController::class, 'sendEmail'])->name('tickets.sendEmail');
    Route::get('/tickets/{ticket}/download', [TicketController::class, 'downloadTicket'])
        ->middleware(function ($request, $next) {
            if (!session('admin_logged_in')) {
                return redirect()->route('admin.login');
            }
            return $next($request);
        })->name('tickets.download');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [TicketController::class, 'create'])->name('home');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
