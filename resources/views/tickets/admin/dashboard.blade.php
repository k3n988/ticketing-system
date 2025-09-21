<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
body { background-color: #f8f9fa; padding-top: 180px; }
.top-bar {
    background-color: #212529;
    padding: 0.5rem 1rem;
    color: #fff;
    font-size: 0.9rem;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1030;
}
.top-bar a { color: #fff; text-decoration: none; }
.top-bar a:hover { color: #adb5bd; }
.navbar { position: fixed; top: 40px; left: 0; width: 100%; z-index: 1020; background-color: #fff; }
.navbar-brand img { max-height: 80px; }
.edit-input { display: none; width: 100%; }
.display-field { display: inline-block; }
</style>
</head>
<body>

<header class="top-bar">
<div class="container d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <span><i class="fas fa-envelope me-2"></i><a href="mailto:comsci.eng@usls.edu.ph">comsci.eng@usls.edu.ph</a></span>
        <span><i class="fas fa-phone me-2"></i><a href="tel:09677636730">09677636730</a></span>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="https://www.facebook.com/CompSciSocUSLS" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>

        <!-- Logout Button -->
        <form action="{{ route('admin.logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger">Logout</button>
        </form>
    </div>
</div>
</header>

<nav class="navbar navbar-light">
<div class="container d-flex justify-content-center">
    <a class="navbar-brand" href="#"><img src="{{ asset('images/logo.png') }}" alt="Logo"></a>
</div>
</nav>

<div class="container py-5">
<h2 class="mb-4">Admin Dashboard</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<p>Welcome, Admin! Here are all the submitted tickets:</p>

<div class="table-responsive mb-5">
<table class="table table-bordered bg-white shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Order Number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>

            <!-- Inline edit form -->
            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="d-flex">
                @csrf
                @method('PUT')

                <td>
                    <span class="display-field">{{ $ticket->customer_name }}</span>
                    <input type="text" name="customer_name" class="form-control edit-input" value="{{ $ticket->customer_name }}" required>
                </td>
                <td>
                    <span class="display-field">{{ $ticket->email }}</span>
                    <input type="email" name="email" class="form-control edit-input" value="{{ $ticket->email }}" required>
                </td>
                <td>
                    <span class="display-field">{{ ucfirst($ticket->status) }}</span>
                    <select name="status" class="form-control edit-input" required>
                        <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $ticket->status === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </td>
                <td>
                    <span class="display-field">{{ $ticket->order_number ?? 'Not set' }}</span>
                    <input type="text" name="order_number" class="form-control edit-input" value="{{ $ticket->order_number }}">
                </td>
                <td class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-primary edit-btn"><i class="fas fa-edit"></i> Edit</button>
                    <button type="submit" class="btn btn-sm btn-success save-btn" style="display:none;"><i class="fas fa-save"></i> Save</button>
                </td>
            </form>

            <!-- Delete / Paid / Send Email -->
            <td class="d-flex gap-1">
                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>

                @if($ticket->status !== 'paid')
                <form action="{{ route('tickets.markPaid', $ticket->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Paid</button>
                </form>
                @endif

                <form action="{{ route('tickets.sendEmail', $ticket->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-warning" {{ $ticket->status !== 'paid' ? 'disabled' : '' }}>
                        <i class="fas fa-paper-plane"></i> Send Ticket
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

{{-- Sent Tickets Section --}}
<h3 class="mb-3">Tickets Already Sent</h3>
<div class="table-responsive">
<table class="table table-bordered bg-white shadow-sm">
    <thead class="table-secondary">
        <tr>
            <th>ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Order Number</th>
            <th>Sent At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets->where('status','paid') as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->customer_name }}</td>
            <td>{{ $ticket->email }}</td>
            <td>{{ $ticket->order_number ?? 'Not set' }}</td>
            <td>{{ $ticket->updated_at->format('F d, Y h:i A') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

</div>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = this.closest('tr');
        row.querySelectorAll('.edit-input').forEach(input => input.style.display = 'block');
        row.querySelectorAll('.display-field').forEach(span => span.style.display = 'none');

        row.querySelector('.save-btn').style.display = 'inline-block';
        this.style.display = 'none';
    });
});
</script>

</body>
</html>
