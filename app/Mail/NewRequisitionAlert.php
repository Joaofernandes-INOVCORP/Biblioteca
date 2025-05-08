<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewRequisitionAlert extends Mailable
{
    use Queueable, SerializesModels;

    protected $requisicao;
    public $user_email;
    /**
     * Create a new message instance.
     */
    public function __construct($requisicao, $to)
    {
        $this->requisicao = $requisicao;
        $this->user_email = $to;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Requisition Alert',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requisicao.alert',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        return $this->from('biblioteca@test.com', "Biblioteca")
            ->to($this->user_email)
            ->view("emails.requisicao.comfirmada")->with([
                    'requisicao' => $this->requisicao
                ]);
    }
}
