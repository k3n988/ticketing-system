<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Format registration date
        $registeredDate = $this->ticket->created_at
            ? Carbon::parse($this->ticket->created_at)->format('F d, Y h:i A')
            : 'N/A';

        // ✅ Generate QR as SVG string
        $qrCodeSvg = QrCode::format('svg')
            ->size(200)
            ->margin(0)
            ->errorCorrection('M')
            ->generate($this->ticket->order_number);

        // ✅ Encode QR as base64 for DomPDF
        $qrCodeSvgBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);

        // ✅ Load and encode logo as base64
        $logoPath = public_path('images/logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }

        // ✅ Generate PDF with base64 logo + QR
        $pdf = Pdf::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
            ])->loadView('tickets.pdf', [
                'ticket'          => $this->ticket,
                'qrCodeSvgBase64' => $qrCodeSvgBase64,
                'logoBase64'      => $logoBase64,
                'registeredDate'  => $registeredDate,
            ]);

        // ✅ Send email with inline SVG QR (logo is shown only in PDF)
        return $this->subject('Your Ticket for ' . $this->ticket->event_name)
            ->view('emails.ticket', [
                'ticket'    => $this->ticket,
                'qrCodeSvg' => $qrCodeSvg,
            ])
            ->attachData(
                $pdf->output(),
                'ticket-' . $this->ticket->order_number . '.pdf',
                ['mime' => 'application/pdf']
            );
    }
}
