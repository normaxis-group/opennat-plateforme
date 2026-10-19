<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Au demandeur : IRICE a validé son compte, il peut se connecter. */
class CompteValide extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'OpenNAT : votre compte est ouvert');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.compte-valide');
    }
}
