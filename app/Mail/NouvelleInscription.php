<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** À IRICE : une demande de compte attend sa validation dans l'administration. */
class NouvelleInscription extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'OpenNAT : demande de compte de '.$this->user->name);
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.nouvelle-inscription');
    }
}
