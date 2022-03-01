<?php

namespace App\Controller\Component;

use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\Filesystem\File;
use Cake\Mailer\Email;


class EmailComponent extends Component
{

    private $host = '';
    private $port = '';
    private $password = '';
    private $user = '';
    private $key = 'email';
    private $tls = true;
    private $from_name = '';
    private $from_email = '';
    private $_dest = [];
    private $_att = [];

    public $components = ['Ftp'];

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        $settings = Cache::read('settings', 'long');
        $this->port = $settings['Email']['port'];
        $this->host = $settings['Email']['host'];
        $this->user = $settings['Email']['user'];
        $this->password = $settings['Email']['password'];
        $this->from_name = $settings['Email']['from_name'];
        $this->from_email = $settings['Email']['from_email'];


        Email::setConfigTransport($this->key, [
            'host' => $this->host,
            'port' => $this->port,
            'username' => $this->user,
            'password' => $this->password,
            'className' => 'Smtp',
            'tls' => $this->tls
        ]);
    }

    /**
     * @param null $subject
     * @param string $layout
     * @param string $template
     * @param array $data
     */
    public function send($subject = null, $layout = 'default', $template = 'default', $data = [])
    {
        try {
            $email = new Email();
            $email->setTransport($this->key);
            $email->setEmailFormat('html');
            $email->setFrom([$this->from_email => $this->from_name]);
            $email->setLayout($layout);
            $email->setTemplate($template);
            $email->setViewVars($data);
            $email->setSubject($subject);
            if (!empty($this->_att)) {
                $email->addAttachments($this->_att);
            }
            $email->addTo($this->_dest);
            $email->send();
            return true;
        } catch (Exception $e) {
            echo 'Exception : ',  $e->getMessage(), "\n";
            return false;
        }
    }


    /**
     * @param $mail
     * @param string $name
     */
    public function add($mail, $name = "")
    {
        $this->_dest[$mail] = $name;
    }

    /**
     * @param $file
     */
    public function addAttachment($file)
    {
        $this->_att = array_merge($this->_att, $file);
    }

}