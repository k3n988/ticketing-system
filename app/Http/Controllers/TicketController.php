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
     * Store a new ticket without sending email.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email'         => 'required|email',
            'quantity'      => 'required|integer|min:1',
            'status'        => 'nullable|string|max:50',
            'price'         => 'nullable|numeric',
            'venue'         => 'nullable|string|max:255',
            'university'    => 'nullable|string|max:255',
            'date'          => 'nullable|date',
            'time'          => 'nullable|string|max:10',
        ]);

        $validated['event_name'] = 'RIFTWALKERS';
        $validated['order_number'] = strtoupper(uniqid('ORD-'));
        $validated['status'] = $validated['status'] ?? 'pending';

        $ticket = Ticket::create($validated);

        return redirect()
            ->route('tickets.create')
            ->with('success', 'Ticket successfully submitted!')
            ->with('last_ticket_id', $ticket->id);
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

    /**
     * Update ticket info (Admin only) - works with inline row editing
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email'         => 'required|email',
            'status'        => 'required|string|max:50',
            'order_number'  => 'nullable|string|max:50', // <--- added for admin editing
        ]);

        $ticket->update($validated); // now includes order_number

        return redirect()->route('admin.dashboard')
            ->with('success', 'Ticket updated successfully!');
    }

    /**
     * Delete ticket (Admin only)
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Ticket deleted successfully!');
    }

    /**
     * Mark ticket as Paid (Admin only)
     */
    public function markPaid(Ticket $ticket)
    {
        $ticket->status = 'paid';
        $ticket->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Ticket marked as Paid!');
    }

    /**
     * Send ticket via email manually (Admin only)
     */
    public function sendEmail(Ticket $ticket)
    {
        $qrCode = QrCode::size(200)->generate($ticket->order_number);

        Mail::to($ticket->email)->send(new TicketCreated($ticket, $qrCode));

        return redirect()->route('admin.dashboard')
            ->with('success', 'Ticket emailed successfully!');
    }
}
