<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class AdminController extends Controller
{
    const ADMIN_EMAIL = "computerscience@gmail.com";
    const ADMIN_PASSWORD = "12345678";

    public function showLogin()
    {
        return view('tickets.admin.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if ($email === self::ADMIN_EMAIL && $password === self::ADMIN_PASSWORD) {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
        }

        return back()->withErrors([
            'login' => 'Invalid email or password.'
        ])->withInput();
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('tickets.create')->with('success', 'Logged out successfully.');
    }

    public function dashboard()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $tickets = Ticket::all();
        return view('tickets.admin.dashboard', compact('tickets'));
    }
}
