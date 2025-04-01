<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GlucoseReportMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(
        protected $pdfContent,
        protected $textPeriod,
        protected $email
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Relatório de Glicemia {$this->textPeriod}",  // Substitua pelo assunto desejado 
            to: [$this->email]          // Destinatário do e-mail
        );
    }

    public function getTextPeriod():string
    {
        return $this->textPeriod;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.glucose_report',
            with: ['textPeriod' => $this->textPeriod]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => $this->pdfContent, 'relatorio_glicemia.pdf')
                ->withMime('application/pdf')
        ];
    }
}
