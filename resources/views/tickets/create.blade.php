<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
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
        .navbar {
            position: fixed;
            top: 40px;
            left: 0;
            width: 100%;
            z-index: 1020;
            background-color: #ffffff;
        }
        .navbar-brand img {
            max-height: 80px;
        }
        .card {
            border: 1px solid #dee2e6;
        }
        body {
            padding-top: 180px;
        }
    </style>
</head>
<body class="bg-white">

<header class="top-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="contact-info">
            <span>
                <i class="fas fa-envelope me-2"></i><a href="mailto:comsci.eng@usls.edu.ph">comsci.eng@usls.edu.ph</a>
            </span>
            <span>
                <i class="fas fa-phone me-2"></i><a href="tel:09677636730">09677636730</a>
            </span>
        </div>
        <div class="social-icons">
            <a href="https://www.facebook.com/CompSciSocUSLS" target="_blank" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
        </div>
    </div>
</header>

<nav class="navbar navbar-light">
    <div class="container d-flex justify-content-center align-items-center">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </a>

        {{-- ✅ Logout Button --}}
        <form action="{{ route('admin.logout') }}" method="POST" class="position-absolute end-0 me-3">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white text-dark">
                    <h3 class="mb-0">Create a Ticket</h3>
                </div>
                <div class="card-body">

                    {{-- ✅ Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success d-flex justify-content-between align-items-center">
                            <span>{{ session('success') }}</span>
                            @if(session('last_ticket_id'))
                                <a href="{{ route('tickets.show', session('last_ticket_id')) }}" class="btn btn-sm btn-success">
                                    View Last Ticket
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Display Validation Errors --}}
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

                        {{-- Event Info --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Event</label>
                            <h4 class="form-control-plaintext fw-bold">RIFTWALKERS</h4>
                            <input type="hidden" name="event_name" value="RIFTWALKERS">
                        </div>

                        {{-- Customer Info --}}
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

                        {{-- Ticket Details --}}
                        <h5 class="mb-3 text-secondary">Ticket Details</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" @selected(old('status') == 'pending')>Pending</option>
                                    <option value="paid" @selected(old('status', 'paid') == 'paid')>Paid</option>
                                    <option value="cancelled" @selected(old('status') == 'cancelled')>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="price" class="form-label">Price (optional)</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="price" 
                                    name="price" 
                                    value="{{ old('price', 30) }}">
                            </div>
                        </div>

                        {{-- Optional Info --}}
                        <h5 class="mb-3 text-secondary">Optional Information</h5>
                        <div class="row g-3 mb-4">
                            <input type="hidden" id="venue" name="venue" value="Cody 11">
                            <input type="hidden" id="date" name="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            <input type="hidden" id="time" name="time" value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                            <div class="col-md-6">
                                <label for="university" class="form-label">University</label>
                                <input type="text" class="form-control" id="university" name="university" value="{{ old('university') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark">Create Ticket</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
