<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Au demandeur : sa demande est enregistrée, IRICE la valide ; il doit d'abord confirmer son adresse. */
class InscriptionRecue extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'OpenNAT : votre demande de compte est enregistrée');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.inscription-recue');
    }
}
