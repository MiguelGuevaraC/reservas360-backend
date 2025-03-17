<?php

namespace App\Http\Controllers;

use App\Mail\EnviarCorreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ApiSendEmailController extends Controller
{
    public function sendEmail(Request $request)
    {
     
        $service = strtoupper($request->input('service','GMAIL2')); // "" o "GMAIL2"

        // Configurar credenciales dinámicamente
        config([
            'mail.mailers.smtp.host'       => env("MAIL_HOST_{$service}"),
            'mail.mailers.smtp.port'       => env("MAIL_PORT_{$service}"),
            'mail.mailers.smtp.username'   => env("MAIL_USERNAME_{$service}"),
            'mail.mailers.smtp.password'   => env("MAIL_PASSWORD_{$service}"),
            'mail.mailers.smtp.encryption' => env("MAIL_ENCRYPTION_{$service}"),
        ]);

        $correo = new EnviarCorreo($request->input('subject'), $request->input('message'));
        $correo->view('emails.correo_html', ['contenido' => $request->input('message')]); // Pasar el contenido como variable a la vista

        // Enviar el correo
        Mail::to($request->input('to'))->send($correo);

        return response()->json(['message' => 'Correo enviado correctamente'], 200);
    }
}
