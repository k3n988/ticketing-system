<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* Top Bar */
        .top-bar {
            background-color: #212529;
            padding: 0.5rem 0;
            color: #ffffff;
            font-size: 0.9rem;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
        }
        .top-bar a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.2s;
        }
        .top-bar a:hover {
            color: #adb5bd;
        }
        .top-bar .contact-info span {
            margin-right: 1.5rem;
        }
        .top-bar .social-icons a {
            font-size: 1.2rem;
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 40px; /* below top bar */
            left: 0;
            width: 100%;
            z-index: 1020;
            background-color: #ffffff;
        }
        .navbar-brand img {
            max-height: 80px;
        }

        /* Body */
        body {
            background-color: #f8f9fa;
            padding-top: 180px; /* avoid overlap with header */
        }
    </style>
</head>
<body>

    {{-- Top Bar --}}
    <header class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="contact-info">
                <span>
                    <i class="fas fa-envelope me-2"></i>
                    <a href="mailto:comsci.eng@usls.edu.ph">comsci.eng@usls.edu.ph</a>
                </span>
                <span>
                    <i class="fas fa-phone me-2"></i>
                    <a href="tel:09677636730">09677636730</a>
                </span>
            </div>
            <div class="social-icons">
                <a href="https://www.facebook.com/CompSciSocUSLS" target="_blank" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </div>
        </div>
    </header>

    {{-- Navbar with Logo --}}
    <nav class="navbar navbar-light">
        <div class="container d-flex justify-content-center">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </a>
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4">Admin Dashboard</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <p>Welcome, Admin! Here are all the submitted tickets:</p>

        <div class="table-responsive">
            <table class="table table-bordered bg-white shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->name }}</td>
                        <td>{{ $ticket->email }}</td>
                        <td>{{ $ticket->subject }}</td>
                        <td>{{ $ticket->message }}</td>
                        <td>{{ $ticket->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>
</html>
