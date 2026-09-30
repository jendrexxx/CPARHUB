<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CparAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $cpar;
    public $deptHead;
    public $prioritylevel;
    public $recordType;

    public function __construct($cpar, $deptHead, $prioritylevel,$recordType)
    {
        $this->cpar = $cpar;
        $this->deptHead = $deptHead;
        $this->prioritylevel = $prioritylevel;
        $this->recordType = $recordType;
    }

    public function envelope(): Envelope
    {
        $type = match ((int) $this->recordType) {
            5 => 'CPAR',
            10 => 'RESULT',
            default => 'CPAR / RESULT',
        };
        $number = match ((int) $this->recordType) {
            5 => $this->cpar->cpar_no ?? 'N/A',
            10 => $this->cpar->result_no ?? 'N/A',
            default => $this->cpar->cpar_no ?? $this->cpar->result_no ?? 'N/A',
        };
        return new Envelope(subject: 'New ' . $type . ' Assigned - ' . $number,);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cpar-assigned',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
