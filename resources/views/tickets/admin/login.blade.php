logi
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
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

        /* Login Card */
        .login-card {
            margin-top: 20px;
        }
        .card-header {
            background-color: #212529;
            color: white;
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

    {{-- Login Card --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow login-card">
                    <div class="card-header text-center">
                        <h4>Admin Login</h4>
                    </div>
                    <div class="card-body">

                        {{-- Error Message --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif

                        {{-- Success Message --}}
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('admin.login.submit') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="form-control" 
                                    placeholder="Enter admin email" 
                                    required 
                                    autofocus
                                >
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control" 
                                    placeholder="Enter password" 
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-dark w-100">Login</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
