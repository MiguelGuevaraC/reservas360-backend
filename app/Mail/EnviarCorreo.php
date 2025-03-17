<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarCorreo extends Mailable
{
    use Queueable, SerializesModels;

    public $asunto;
    public $contenido;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($asunto, $contenido)
    {
        $this->asunto = $asunto;
        $this->contenido = $contenido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->asunto)
            ->view('emails.correo_html')
            ->with(['contenido' => $this->contenido]); // Pasar el contenido a la vista
    }
}
