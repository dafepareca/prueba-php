<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 16/05/17
 * Time: 12:25 PM
 */

namespace App\Mailer\Preview;

use DebugKit\Mailer\MailPreview;

class UserMailPreview extends MailPreview
{
    public function welcome()
    {

        return $this->getMailer("User")
            ->welcome()
            ->set(["activationToken" => "dummy-token"]);
    }

    public function acuerdo()
    {

        return $this->getMailer("User")
            ->acuerdo()
            ->set(["activationToken" => "dummy-token"]);
    }
}
