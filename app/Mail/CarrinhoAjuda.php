<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarrinhoAjuda extends Mailable
{
    use Queueable, SerializesModels;

    public $user_email;
    /**
     * Create a new message instance.
     */
    public function __construct($to)
    {
        $this->user_email = $to;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Carrinho Ajuda',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
            ->subject("Tem o carrinho à mais de uma hora, precisa de ajuda?");
    }
}
