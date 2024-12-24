<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $capexData;
    public $nextApprover;

    public function __construct($capexData, $nextApprover)
    {
        $this->capexData = $capexData;
        $this->nextApprover = $nextApprover;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permintaan Persetujuan CAPEX Baru',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
