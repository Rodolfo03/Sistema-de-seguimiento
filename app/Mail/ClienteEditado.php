<?php

namespace App\Mail;

use App\Models\Equipo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClienteEditado extends Mailable
{
    use Queueable, SerializesModels;

    public $equipo;
    public $link;

    public function __construct(Equipo $equipo, $link)
    {
        $this->equipo = $equipo;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Actualización de Estado de tu Equipo')
                    ->view('emails.cliente_editado') // Vista que contiene el correo
                    ->with([
                        'equipo' => $this->equipo,
                        'link' => $this->link,
                    ]);
    }
}
