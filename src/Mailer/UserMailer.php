<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 16/05/17
 * Time: 12:24 PM
 */

namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    public function welcome()
    {

        return $this // Returning the chain is a good idea :)
        ->to('waltercabezasr@email.com')
            ->setSubject(sprintf("Welcome %s", 'walter cabezas'))
            ->setEmailFormat('html')
            ->setTemplate("envio_clave") // By default template with same name as method name is used.

            ->setViewVars(
                [
                    'mensaje' => 'Su datos de acceso al sistema Dataweb son :',
                    'usuario' => 'www@email.com',
                    'clave' => 'dsgdfhgret',
                    'nombre' => 'walter cabezas',
                    'link' => "http://".$_SERVER['HTTP_HOST'].'/',
                ]
            )
            ->set(["user" => []]);
    }

    public function acuerdo()
    {

        return $this // Returning the chain is a good idea :)
        ->to('waltercabezasr@email.com')
            ->setSubject('Siempre tendrá nuestra orientación financiera')
            ->setEmailFormat('html')
            ->setTemplate("accord") // By default template with same name as method name is used.
            ->setViewVars(
                [
                    'message' => 'Lo invitamos a conocer las condiciones de su negociación en el archivo adjunto, al igual que el detalle de la situación de sus obligaciones.',
                    'name' => 'walter cabezas',
                ]
            )
            ->set(["user" => []]);
    }

}