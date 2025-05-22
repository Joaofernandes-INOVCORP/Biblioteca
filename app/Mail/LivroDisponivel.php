<?php

namespace App\Mail;

use App\Models\Livro;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LivroDisponivel extends Mailable
{
    use Queueable, SerializesModels;

    public Livro $livro;

    /**
     * Create a new message instance.
     */
    public function __construct(Livro $livro)
    {
        $this->Livro = $livro;
    }

    public function build()
    {
        return $this->subject("O livro '{$this->livro->nome}' já está disponível.")
            ->view('emails.livro_disponivel')
            ->with(['livro' => $this->livro]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Livro Disponivel',
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
}
