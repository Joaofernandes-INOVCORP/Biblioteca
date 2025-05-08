<?php

namespace App\Mail;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequisicaoConfirmada extends Mailable
{
    use Queueable, SerializesModels;

    protected Requisicao $requisicao;
    public string $user_email;
    /**
     * Create a new message instance.
     */
    public function __construct(Requisicao $requisicao, string $to)
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
            subject: 'Requisicao Confirmada',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requisicao.confirmada',
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
            ->view("emails.requisicao.alert")->with([
                    'requisicao' => $this->requisicao
                ]);
    }
}
