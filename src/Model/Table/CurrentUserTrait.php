<?php
namespace App\Model\Table;

use Cake\Network\Request;
use Cake\Network\Session;

trait CurrentUserTrait 
{
    public function currentUser()
    {
        $request = Request::createFromGlobals();
        $session = new Session();
        return [
            'id' => $session->read('Auth.User.session_id'),
            'ip' => $request->env('REMOTE_ADDR'),
            'url' => $request->here(),
            'description' => 'Cambio realizado por: '.$session->read('Auth.User.name').' / '. $session->read('Auth.User.email'),
        ];
    }
}