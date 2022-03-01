<?php
namespace App\Controller\Component;
use App\Model\Table\DocumentsTable;
use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\Core\App;
use Cake\Core\Exception\Exception;
use Cake\ORM\Entity;

class FtpComponent extends Component {

    var $path = '';
    var $port = '';
    var $server = '';
    var $password = '';
    var $user = '';

    public function initialize(array $config){
        $settings = Cache::read('settings', 'long');
        $this->port = $settings['FileRepository']['port'];
        $this->path = $settings['FileRepository']['path'];
        $this->server = $settings['FileRepository']['server'];
        $this->user = $settings['FileRepository']['user'];
        $this->password = $settings['FileRepository']['password'];
    }

    public function download_ftp_file($document){

        try {
            $file = $document->file;
            $local_file = TMP.'/files/'.$file;
            $server_file = $document->file_dir.$file;

            // Establecer la conexión
            $ftp_server = $this->server;
            $ftp_user_name = $this->user;
            $ftp_user_pass = $this->password;
            $conn_id = ftp_connect($ftp_server, $this->port);

            if(!$conn_id){
                throw new Exception('No se pudo conectar con '.$this->server,403);
            }

            // Loguearse con usuario y contraseña
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

            ftp_pasv($conn_id, true);

            // Descarga el $server_file y lo guarda en $local_file
            if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage(),500);
        } finally {
            ftp_close($conn_id);
        }

    }

}