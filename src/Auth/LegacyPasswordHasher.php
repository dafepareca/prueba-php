<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 10/12/18
 * Time: 10:35 AM
 */

namespace App\Auth;

use Cake\Auth\AbstractPasswordHasher;
use Cake\Log\Log;

class LegacyPasswordHasher extends AbstractPasswordHasher
{
    var $codigo = '';

    public function __construct(array $config = [])
    {
        $this->codigo = sha1('$-%Dav1#');
        parent::__construct($config);
    }

    public function hash($password)
    {
        return sha1($password).$this->codigo;
    }

    public function check($password, $hashedPassword)
    {
        return $password.$this->codigo === $hashedPassword;
    }
}