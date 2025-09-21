<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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
        .top-bar a { color: #ffffff; text-decoration: none; transition: color 0.2s; }
        .top-bar a:hover { color: #adb5bd; }
        .top-bar .contact-info span { margin-right: 1.5rem; }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 40px;
            left: 0;
            width: 100%;
            z-index: 1020;
            background-color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .navbar-brand img { max-height: 80px; margin: 0 10px; }

        /* Body */
        body { padding-top: 200px; background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }

        /* Cards */
        .ticket-card, .event-details-card {
            max-width: 600px; 
            margin: 0 auto 1.5rem auto;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            background-color: #ffffff;
            transition: transform 0.2s;
        }
        .ticket-card:hover, .event-details-card:hover {
            transform: translateY(-3px);
        }
        .card-header {
            border-bottom: none;
            background-color: #ffffff;
            font-weight: 700;
            font-size: 1.4rem;
            color: #212529;
        }
        .card-body input.form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: all 0.2s;
        }
        .card-body input.form-control:focus {
            border-color: #3a3d98;
            box-shadow: 0 0 0 0.2rem rgba(58,61,152,.15);
        }
        .btn-dark {
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            transition: all 0.2s;
        }
        .btn-dark:hover {
            background-color: #3a3d98;
        }

        /* Images */
        .eventlogo, .ticketlogo { width: 100%; border-radius: 12px; margin-bottom: 15px; }

        /* Footer */
        footer {
            background-color: #ffffff; 
            color: #212529; 
            padding: 20px 0; 
            text-align: center;
            border-top: 1px solid #dee2e6;
        }
        footer a { color: #212529; text-decoration: none; transition: color 0.2s; }
        footer a:hover { color: #3a3d98; }
    </style>
</head>
<body>

<header class="top-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="contact-info">
            <span><i class="fas fa-envelope me-2"></i><a href="mailto:comsci.eng@usls.edu.ph">comsci.eng@usls.edu.ph</a></span>
            <span><i class="fas fa-phone me-2"></i><a href="tel:09677636730">09677636730</a></span>
        </div>
        <div class="d-flex align-items-center">
            <div class="social-icons me-3">
                <a href="https://www.facebook.com/CompSciSocUSLS" target="_blank" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </div>
            <a href="{{ route('admin.login') }}" class="btn btn-sm btn-outline-dark">Login</a>
        </div>
    </div>
</header>

<nav class="navbar navbar-light bg-white fixed-top shadow-sm" style="top: 40px;">
    <div class="container d-flex justify-content-center">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </a>
    </div>
</nav>

<div class="container">
    <div class="row align-items-start">
        <div class="col-lg-6 text-center">
            <img src="{{ asset('images/eventlogo.jpg') }}" alt="Event Logo" class="img-fluid eventlogo">
        </div>

        <div class="col-lg-5">
            <div class="card ticket-card">
                <div class="card-header text-center">Order Ticket</div>
                <div class="card-body">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tickets.store') }}" method="POST">
                        @csrf
                        <h5 class="mb-3 text-secondary">Customer Information</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Customer Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="year_level" class="form-label">Year & Level</label>
                                <input type="text" class="form-control" id="year_level" name="year_level" value="{{ old('year_level') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="student_id" class="form-label">ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id') }}">
                            </div>
                        </div>

                        {{-- Hidden Fields --}}
                        <input type="hidden" name="order_number" value="{{ old('order_number', \Str::uuid()) }}">
                        <input type="hidden" name="event_name" value="RIFTWALKERS">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="status" value="pending">
                        <input type="hidden" name="price" value="30">
                        <input type="hidden" name="venue" value="Cody 11">
                        <input type="hidden" name="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        <input type="hidden" name="time" value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                        <input type="hidden" name="university" value="">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card event-details-card p-4">
                <div class="row mb-3">
                    <div class="col-6">
                        <h5 class="fw-bold mb-1">Ticket Price</h5>
                        <p class="mb-0">₱30</p>
                    </div>
                    <div class="col-6">
                        <h5 class="fw-bold mb-1">Venue Details</h5>
                        <p class="text-uppercase mb-0 fw-bold">Venue</p>
                        <p class="mb-0">Cody 24 2nd floor</p>
                        <p class="mb-0"><a href="#" class="text-primary">University of Saint La Salle</a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h5 class="fw-bold mb-1">Date and Time</h5>
                        <p class="mb-0">Sept 22 - 26 @8am - 5pm</p>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-center">
                <img src="{{ asset('images/ticketlogo.jpg') }}" alt="Ticket Logo" class="img-fluid ticketlogo">
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <p class="mb-1">&copy; 2025 Computer Science Society - USLS. All Rights Reserved.</p>
        <p class="mb-0">
            <a href="mailto:comsci.eng@usls.edu.ph">comsci.eng@usls.edu.ph</a> | 
            <a href="https://www.facebook.com/CompSciSocUSLS" target="_blank">Facebook</a>
        </p>
    </div>
</footer>

</body>
</html>
