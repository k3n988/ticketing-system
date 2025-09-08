<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Ticket - {{ $ticket->event_name ?? 'Event' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #fff;
            padding: 20px;
        }
        .ticket-container {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 650px;
            margin: auto;
            border: 2px solid #1a237e;
            overflow: hidden;
            padding: 20px;
        }
        .ticket-table {
            width: 100%;
            border-collapse: collapse;
        }
        .ticket-table td {
            vertical-align: top;
            padding: 0;
        }
        .ticket-left {
            width: 65%;
        }
        .ticket-right {
            width: 35%;
            text-align: right;
            position: relative;
        }
        .ticket-left h1 {
            font-size: 26px;
            margin: 10px 0;
            color: #1a237e;
        }
        .ticket-left h2 {
            font-size: 16px;
            margin: 5px 0;
            font-weight: normal;
        }
        .ticket-left h3 {
            font-size: 12px;
            margin: 12px 0 4px;
            text-transform: uppercase;
            color: #333;
        }
        .ticket-left p {
            margin: 2px 0;
            font-size: 13px;
            color: #555;
        }
        .logo {
            text-align: left;
            margin-bottom: 15px;
        }
        .logo img {
            width: 400px;
            height: auto;
        }
        
        /* QR Code Container and Styling */
        .qr-container {
            width: 180px; /* Increased width to enlarge QR code */
            height: 180px; /* Increased height to enlarge QR code */
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            margin-top: 60px; /* Reduced margin-top to move it closer to details */
            transform: translateX(-20px); /* Keeping this for left alignment, adjust if needed */
        }
        .qr-code-img {
            width: 100%;
            height: 100%;
            padding: 5px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <table class="ticket-table">
            <tr>
                <td class="ticket-left">
                    <div class="logo">
                        <img src="{{ $logoBase64 ?? '' }}" alt="Event Logo">
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
                    <p>Registered {{ $ticket->created_at ? \Carbon\Carbon::parse($ticket->created_at)->format('F d, Y') : 'N/A' }}</p>
                    <h3>Ticket</h3>
                    <p>{{ $ticket->event_name ?? 'Event' }}</p>
                </td>
                <td class="ticket-right">
                    <div class="qr-container">
                        @if(!empty($qrCodeSvgBase64))
                            <img src="{{ $qrCodeSvgBase64 }}" alt="QR Code" class="qr-code-img">
                        @else
                            <p>QR code not available</p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>