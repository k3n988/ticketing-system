<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Ticket - {{ $ticket->event_name ?? 'Event' }}</title>
    <style>
        /* Body styles */
        body {
            font-family: 'Arial', sans-serif;
            background: #eceff1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        /* Ticket container */
        .ticket {
            background: #ffffff;
            border-radius: 20px;
            width: 750px; /* wider for big QR */
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        /* Left section */
        .ticket-left {
            width: 65%;
            padding: 30px;
        }

        .ticket-left h1 {
            font-size: 32px;
            margin: 10px 0;
            color: #1a237e;
        }

        .ticket-left h2 {
            font-size: 18px;
            margin: 5px 0;
            font-weight: normal;
        }

        .ticket-left h3 {
            font-size: 14px;
            margin: 15px 0 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #333;
        }

        .ticket-left p {
            margin: 2px 0;
            font-size: 14px;
            color: #555;
        }

        /* Logo styling */
        .ticket-left .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .ticket-left .logo img {
            width: 400px;
            height: auto;
        }

        /* Right section (QR code only, no blue bg) */
        .ticket-right {
            width: 35%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: none; /* ✅ remove blue background */
        }

        .ticket-right svg {
            width: 220px;   /* enlarged QR code */
            height: 220px;
            background: #fff; /* keep white background inside QR */
            padding: 15px;
            border-radius: 12px;
        }

        .ticket-right p {
            text-align: center;
            font-size: 12px;
            color: #555; /* changed from white to dark since bg is gone */
            padding: 0 5px;
        }

        /* Responsive adjustments */
        @media(max-width: 700px) {
            .ticket {
                flex-direction: column;
                width: 90%;
            }

            .ticket-left,
            .ticket-right {
                width: 100%;
                text-align: center;
                padding: 20px;
            }

            .ticket-right svg {
                width: 180px;
                height: 180px;
            }
        }
    </style>
</head>
<body>

    <div class="ticket">

        <!-- Left side: Logo and ticket info -->
        <div class="ticket-left">
            <!-- Logo -->
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Event Logo">
            </div>

            <h2>Computer Science Society – {{ $ticket->university ?? 'University of ST. La Salle' }}</h2>
            <h1>{{ $ticket->event_name ?? 'Event Name' }}</h1>

            <p><strong>{{ $ticket->venue ?? 'Venue N/A' }}</strong></p>
            <p>
                {{ $ticket->date ? \Carbon\Carbon::parse($ticket->date)->format('F d, Y') : 'Date N/A' }},
                {{ $ticket->time ? \Carbon\Carbon::parse($ticket->time)->format('h:i A') : 'Time N/A' }}
            </p>

            <h3>Issued To</h3>
            <p>{{ $ticket->customer_name ?? 'Customer Name N/A' }}</p>

            <h3>Order Number</h3>
            <p>{{ $ticket->order_number ?? 'N/A' }}</p>

            <p>Registered {{ $ticket->created_at ? $ticket->created_at->format('F d, Y') : 'N/A' }}</p>

            <h3>Ticket</h3>
            <p>{{ $ticket->event_name ?? 'Event' }} </p>
        </div>

        <!-- Right side: QR code enlarged -->
        <div class="ticket-right">
            @if(!empty($ticket->order_number))
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->generate($ticket->order_number) !!}
            @else
                <p>QR code not available</p>
            @endif
        </div>

    </div>

</body>
</html>
