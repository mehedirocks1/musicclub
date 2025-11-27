<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailSender extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $viewFile;
    public $data;
    public $isMarkdown;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subjectLine, string $viewFile, array $data = [], bool $isMarkdown = false)
    {
        $this->subjectLine = $subjectLine;
        $this->viewFile = $viewFile;
        $this->data = $data;
        $this->isMarkdown = $isMarkdown;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: $this->isMarkdown ? $this->viewFile : null,
            view: $this->isMarkdown ? null : $this->viewFile,
            with: $this->data
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}