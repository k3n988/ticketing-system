<h2>Hello {{ $ticket->customer_name }},</h2>

<p>Thank you for your order! Here’s your ticket:</p>

<p>
    <strong>Event:</strong> {{ $ticket->event_name }}<br>
    <strong>Order Number:</strong> {{ $ticket->order_number }}
</p>

<!-- QR Code as Base64 Image -->
<div style="margin-top: 15px; text-align: center;">
    @if(!empty($qrCodeSvg))
        <img src="data:image/svg+xml;base64,{{ base64_encode($qrCodeSvg) }}" 
             alt="QR Code" width="150" height="150">
    @else
        <p>QR code not available</p>
    @endif
</div>
