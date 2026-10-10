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

    public $request;
    public $recipient;
    public $prioritylevel;
    public $recordType;
    public $branch_id;

    public function __construct(
        $request,
        $recipient,
        $prioritylevel,
        $recordType,
        $branch_id
    ) {
        $this->request = $request;
        $this->recipient = $recipient;
        $this->prioritylevel = $prioritylevel;
        $this->recordType = (int) $recordType;
        $this->branch_id = $branch_id;
    }

    public function envelope(): Envelope
    {
        $number = $this->recordType === 10
            ? ($this->request->result_no ?? 'N/A')
            : ($this->request->cpar_no ?? 'N/A');

        $type = $this->recordType === 10
            ? 'RESULT'
            : 'CPAR';

        return new Envelope(
            subject: "New {$type} Assigned - {$number}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cpar-assigned',
            with: [
                'request' => $this->request,
                'recipient' => $this->recipient,
                'prioritylevel' => $this->prioritylevel,
                'recordType' => $this->recordType,
                'branch_id' => $this->branch_id,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
