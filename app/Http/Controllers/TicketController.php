<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreated;

class TicketController extends Controller
{
    /**
     * Show the ticket creation form.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a new ticket and send via email.
     */
    public function store(Request $request)
    {
        // Validate inputs
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email'         => 'required|email',
            'quantity'      => 'required|integer|min:1',
            'status'        => 'required|string|max:50',
            'price'         => 'nullable|numeric',
            'venue'         => 'nullable|string|max:255',
            'university'    => 'nullable|string|max:255',
            'date'          => 'nullable|date',
            'time'          => 'nullable|string|max:10',
        ]);

        // Force event name
        $validated['event_name'] = 'RIFTWALKERS';

        // Generate unique order number
        $validated['order_number'] = strtoupper(uniqid('ORD-'));

        // Save ticket
        $ticket = Ticket::create($validated);

        // Generate QR code as inline SVG
        $qrCode = QrCode::size(200)->generate($ticket->order_number);

        // Send email to customer
        Mail::to($ticket->email)->send(new TicketCreated($ticket, $qrCode));

        // ✅ Redirect back to create page with success message
        return redirect()
            ->route('tickets.create')
            ->with('success', 'Ticket created and emailed successfully!');
    }

    /**
     * Show ticket details.
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Download ticket as PDF with QR Code.
     */
    public function downloadTicket(Ticket $ticket)
    {
        $qrCode = base64_encode(
            QrCode::size(200)->generate('ORDER: ' . $ticket->order_number)
        );

        $pdf = Pdf::loadView('tickets.pdf', [
            'ticket'         => $ticket,
            'qrCode'         => $qrCode,
            'registeredDate' => $ticket->created_at
                ? Carbon::parse($ticket->created_at)->format('F d, Y h:i A')
                : 'N/A',
        ]);

        return $pdf->download('ticket-' . $ticket->order_number . '.pdf');
    }
}
