<?php
namespace App\Shell;

use App\Model\Entity\Log;
use App\Model\Entity\Obligation;
use App\Model\Entity\Payment;
use App\Model\Table\HistoryStatusesTable;
use App\Model\Table\TypeObligationsTable;
use App\Model\Table\ConditionsTable;
use App\Model\Table\TypesConditionsTable;
use Cake\Cache\Cache;
use Cake\Console\Shell;
use Cake\Mailer\Email;
use Cake\Utility\Time;
use Cake\Core\Configure;
use Cake\Controller\Component;


/**
 * CrediAmigo shell command.
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\LogsFilesTable $LogsFiles
 * @property Shell AdjustedObligations
 *
 */
class DaviviendaShell extends Shell
{

    private $parametros = [];
    private $error = [];
    private $conditions;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->conditions = $this->fetchConditios();

        $settings = Cache::read('settings', 'long');
        if (empty($settings)) {
            $settings = $this->fetchSettings();
            Cache::write('settings', $settings, 'long');
        }
        Configure::write($settings);

        $this->parametros = $settings['Parametros'];
    }

    /**
     * FetchSettings method
     *
     * @return \Cake\Network\Response|null|void
     */
    private function fetchSettings(){
        $this->loadModel('Settings');
        $keys = $this->Settings->getKeyValuePairs();
        return $keys;
    }

    /**
     * main() method.
     *
     * @return bool|int|null Success or error code.
     */
    public function main(){
        $this->out('Begin process main: '.date('Y-m-d H:i:s'));
    }


    private function fetchConditios(){
        $this->loadModel('Conditions');
        $keys = $this->Conditions->getKeyValuePairs();
        return $keys;
    }

    public function gestion($date = null) /* LOG COMERCIAL AUTOMATIZADO*/
    {

        $this->out('Inicio proceso...');

        if(!is_null($date)) {
            $fechaFin = date($date.' 20:50:00');
            $fechaInicio = strtotime('-24 hours', strtotime($fechaFin));
            $fechaInicio = date('Y-m-d 20:50:00', $fechaInicio);
            $nameFile = 'PREGESTIONDAVI'. date('Ymd', strtotime($date)) . '.txt';
            //$nameFile = 'GestionDAVI'. date('Ymd', strtotime($date)) . '.txt';
        }else{
            $fechaFin = date('Y-m-d 20:50:00');
            $date = date('Y-m-d', strtotime($fechaFin));
            $fechaInicio = strtotime('-24 hours', strtotime($fechaFin));
            $fechaInicio = date('Y-m-d 20:50:00', $fechaInicio);
            $nameFile = 'PREGESTIONDAVI'. date('Ymd', strtotime($date)) . '.txt';
            //$nameFile = 'GestionDAVI'. date('Ymd') . '.txt';
        }

        $date = date('Y-m-d', strtotime($fechaFin));

        $this->loadModel('CustomerTypeIdentifications');
        $this->loadModel('Customers');
        $this->loadModel('Coordinador');
        $this->loadModel('AdjustedObligations');
        $this->loadModel('HistoryCustomers');
        
        $obligations = $this->AdjustedObligations->find()
            ->contain(['AdjustedObligationsDetails'])
            ->where([
                'pending_committee' => 0,
                #'id > ' => 279,
                #"DATE_FORMAT(date_negotiation,'%Y-%m-%d')" => $date //'2018-06-05'//
            ])->andWhere(function ($exp) use ($fechaInicio, $fechaFin){
                return $exp->between('date_negotiation',$fechaInicio,$fechaFin);
            })
        ->all();

        $historys = $this->HistoryCustomers->find()
            ->contain(['HistoryDetails'])
            ->where (function ($exp) use ($fechaInicio, $fechaFin){
                return $exp->between('created',$fechaInicio,$fechaFin);
            })
            ->all();
        
        $filePath = TMP . 'files/' . $nameFile;
        $fp2 = fopen($filePath, 'w');
        $data = [];
        /** @var  $obligation AdjustedObligation */

        foreach ($obligations as $obligation) {
            foreach ($historys as $history){
                foreach ($obligation->adjusted_obligations_details as $detail) {
                    if ($history->log_id == $obligation->log_id){
                        $negociacion = Obligation::getCogigo($detail->type_strategy);
                        $necesitaPagareUnificacion = 1;
                        $necesitaPagareACPK = 1;
                        foreach ($history->history_details as $hDetail){
                            if($hDetail->type_strategy == 1){
                                if($hDetail->pagare == 0 || $hDetail->pagare == null){
                                 $necesitaPagareUnificacion = 0;
                              } 
                            }
                            if($hDetail->type_strategy == 12){
                                 if($hDetail->pagare == 0 || $hDetail->pagare == null){
                                     $necesitaPagareACPK = 0;
                                 }
                            }
                        }
                        $pagoInicial = (is_null($obligation->payment_agreed))?$detail->payment_agreed:$obligation->payment_agreed;
                        $tipoResultado = 'DATA';
    
    
                        if ($detail->type_obligation == 'TDC') {
                            $tamano = strlen($detail->obligation);
                            $numeroObligacion = substr($detail->obligation, $tamano - 16, 16);
                        }elseif ($detail->type_obligation == 'SOB') {
                            $tamano = strlen($detail->obligation);
                            $numeroObligacion = substr($detail->obligation, $tamano - 16, 16);
                        } else {
                            $numeroObligacion = $detail->obligation;
                        }
    
                        if($obligation->approved_committee){
                            $tipoResultado = 'CA';
                        }
                        if (!empty($obligation->coordinator_id) AND $obligation->approved_committee == 0) {
                            $comentario = $obligation->reason_rejection;
                            $tipoResultado = 'CN';
                        } elseif (in_array($detail->type_strategy, [6, 7, 8])) {
                            $comentario = $detail->strategy . ', con un valor a pagar de  ' . number_format($detail->new_fee);
                        } elseif (in_array($detail->type_strategy, [12])) {
                            $comentario = $detail->strategy . ', condonacion inicial  de  ' . ($obligation->initial_condonation * 100) . '% por valor de ' . number_format($obligation->value_initial_condonation) .
                                ', condonacion final de  ' . ($obligation->end_condonation * 100) . '% por valor de ' . $obligation->value_end_condonation . ', pago inicial de ' . number_format($obligation->initial_payment) . ' plazo de ' . $detail->months_term . ' meses y cuota proyectada de ' . number_format($detail->new_fee);
                            $pagoInicial = $obligation->initial_payment;
                        } elseif (in_array($detail->type_strategy, [13])) {
                            $comentario = $detail->strategy . ', Tasa efectiva anual ' . round($detail->annual_effective_rate, 1) . '%,  Tasa Nominal ' . round($detail->monthly_rate, 1) . '%, con un unico pago de por valor de  ' . number_format($detail->new_fee);
                            if(empty($pagoInicial)){
                                $pagoInicial = $detail->new_fee;
                            }
                        } else {
                            $comentario = $detail->strategy . ', Tasa efectiva anual ' . round($detail->annual_effective_rate, 1) . '%,  Tasa Nominal ' . round($detail->monthly_rate, 1) . '%, con un plazo de ' . $detail->months_term . ' meses y cuota proyectada de ' . number_format($detail->new_fee);
                        }
    
                        if(empty($pagoInicial)){
                            $pagoInicial = 1;
                        }
    
                        $comentario = $this->sanear_string($comentario);

                        //'fecha_documentacion' => (empty($obligation->documentation_date) OR is_null($obligation->documentation_date) ) ? $obligation->date_negotiation->format('Ymd') : $obligation->documentation_date->format('Ymd'),
                        //'fecha_pago' =>  (empty($obligation->documentation_date) OR is_null($obligation->documentation_date) ) ? $obligation->date_negotiation->format('Ymd') : $obligation->documentation_date->format('Ymd'),
                        //Valido si es vacio o nulo
                        if (empty($obligation->documentation_date) OR is_null($obligation->documentation_date)) {
                            $fecha_Documentacion_Pago = $obligation->date_negotiation->format('Ymd');
                        }else{
                            $fecha_Documentacion_Pago = $obligation->documentation_date->format('Ymd');
                        }

                        $fields = [
                            'accion' => 'INFC',
                            'type_identification' => str_pad($obligation->type_identification, 2, '0', STR_PAD_LEFT),
                            'identification' => str_pad($obligation->identification, 15, '0', STR_PAD_LEFT),
                            'fecha' => $obligation->date_negotiation->format('Ymd'),
                            'hora' => $obligation->date_negotiation->format('Hms'),
                            'obligation' => str_pad($numeroObligacion, 20, ' ', STR_PAD_RIGHT),
                            'telefono' => '0000000000',
                            'extension' => '000000',
                            'ciudad' => '00000000000',
                            'tipo_telefono' => '00',
                            'tipo_direccion' => (!empty($obligation->customer_email)) ? '06' : '00',
                            'customer_email' => str_pad($obligation->customer_email, 60, ' ', STR_PAD_RIGHT),
                            'codigo_gestor' => str_pad($obligation->code_manager, 10, ' ', STR_PAD_RIGHT),
                            'codigo_recuperador' => str_pad($obligation->company_code, 4, ' ', STR_PAD_RIGHT),
                            'tipo_resultado' => str_pad($tipoResultado, 4, ' ', STR_PAD_RIGHT),
                            'contacto' => str_pad('', 30, ' ', STR_PAD_RIGHT),
                            'motivo_no_pagp' => str_pad('', 4, ' ', STR_PAD_RIGHT),
                            'nivel_ingresos' => str_pad('', 4, ' ', STR_PAD_RIGHT),
                            'negociacion' => str_pad($negociacion, 6, ' ', STR_PAD_RIGHT),
                            'fecha_2' => $obligation->date_negotiation->format('Ymd'),
                            'hora_2' => $obligation->date_negotiation->format('Hms'),
                            'fecha_documentacion' => $fecha_Documentacion_Pago,
                            'fecha_pago' =>  $fecha_Documentacion_Pago,
                            'valor_negociacion' => str_pad($pagoInicial, 15, '0', STR_PAD_LEFT),
                            'codigo_reporte' => str_pad('', 4, ' ', STR_PAD_RIGHT),
                            'fecha_reporte' => '00000000',
                            'hora_reporte' => '000000',
                            'tarea' => str_pad('', 4, ' ', STR_PAD_RIGHT),
                            'fecha_tarea_desde' => '00000000',
                            'fecha_tarea_hasta' => '00000000',
                            'hora_tarea_desde' => '000000',
                            'hora_tarea_hasta' => '000000',
                            'comentario' => str_pad($comentario, 200, ' ', STR_PAD_RIGHT),
                            'comentario_terceros' => str_pad('', 200, ' ', STR_PAD_RIGHT),
                            'consecutivo' => '0000000000',
                            'consecutivo_relativo' => '0000000000',
                            'fecha_generacion' => '00000000',
                            'hora_generacion' => '000000',
                            'sequential_obligation' =>  $detail->sequential_obligation,
                            // 'credit_payment_day' => "  ",
                            // 'documents_required' => " ",
                            // 'is_uvr' => $detail->currency == 'UVR' ? "1" : " "
                        ];
                        
                        if ($detail->type_strategy == 1) {
                            $fields['negociacion'] = Obligation::getCogigo($detail->type_strategy, $necesitaPagareUnificacion);
                        }
    
                        if ($detail->type_strategy == 12) {
                            $fields['negociacion'] = Obligation::getCogigo($detail->type_strategy, $necesitaPagareACPK);
                        }

                        if($detail->type_strategy == 3){
                            if($obligation->retrenched_policy == 3){
                                $fields['negociacion'] = str_pad(Obligation::getCogigo(3, 0),6,' ',STR_PAD_RIGHT);
                            }
                        }
                        
                        // if ($fields['negociacion'] == "CIAPCC") {
                        $data[] = $fields;
                        // }
                    
                
                    }
                }
            }
        }

        $this->out('Escribiendo archivo...');
        foreach ($data as $file) {
            foreach ($file as $key => $col) {
                if ($key != 'sequential_obligation') {
                    if (!fwrite($fp2, $col . ';')) {
                        echo 'error 1 ' . $key;
                    }
                } else {
                    if (!fwrite($fp2, $col)) {

                    }
                }
            }
            fwrite($fp2, '' . PHP_EOL);
        }
        fclose($fp2);
        //return true;
        $message = '<p>Buenas Noches,</p><br><br><p>Adjunto envío el archivo de gestión del día, favor cambiar la codificación</p><br><br> Gracias';

        try {
            $this->out('Enviando correo...');
            $email = new Email();

            $settings = Cache::read('settings', 'long');

            $fromName = $settings['Email']['from_name'];
            $from = $settings['Email']['from_email'];
            $port = $settings['Email']['port'];
            $host = $settings['Email']['host'];
            $user = $settings['Email']['user'];
            $password = $settings['Email']['password'];

            $email::setConfigTransport('email', [
                'host' => $host,
                'port' => $port,
                'username' => $user,
                'password' => $password,
                'className' => 'Smtp',
                'tls' => true
            ]);

            $email->setTransport('email');
            $email->setEmailFormat('html');
            $email->setFrom([$from => $fromName]);
            $email->setLayout('');
            $email->setTemplate('message');
            $email->setViewVars([
                'message' => $message
            ]);

            $nameFile = explode('/', $filePath);
            $nameFile = end($nameFile);

            $email->setSubject('Gestión Davinegociador ' . $date);
            #$email->addAttachments([$nameFile => $filePath]);
            
            $gestionEmails = [];
            foreach (explode(";", $settings['EnvioGestion']['gestion_emails_lista']) as $emailTo) {
                $gestionEmails[$emailTo] = 'Gestion';
            };

            $email->addTo($gestionEmails);
            $email->addAttachments([$nameFile => $filePath]);
            $email->send();
            $this->out('Fin de proceso.');
            return true;
        } catch (Exception $e) {
            $this->out('Error enviando correo...');
            echo 'Exception : ', $e->getMessage(), "\n";
            return false;
        }
    }

    public function testEmail(){
        try {
            $this->out('Enviando correo...');
            $email = new Email();

            $email::setConfigTransport('email', [
                'host' => 'smtp.office365.com',
                'port' => 587,
                'username' => 'davinegociador@sgnpl.com',
                'password' => 'Colombia2020*',
                'className' => 'Smtp',
                'tls' => true
            ]);

            $email->setTransport('email');
            $email->setEmailFormat('html');
            $email->setFrom(['davinegociador@sgnpl.com' => 'Davinegociador']);
            $email->setLayout('');
            $email->setTemplate('message');
            $email->setViewVars([
                'message' => "prueba mensaje"
            ]);

            $email->setSubject('Prueba Gestión Davinegociador ' . date('Y-m-d'));
            #$email->addAttachments([$nameFile => $filePath]);
            $email->addTo([
                'w.cabezas@sistemcobro.com' => 'walter cabezas'
            ]);

            $email->send();
            $this->out('Fin de proceso.');
            return true;
        } catch (Exception $e) {
            $this->out('Error enviando correo...');
            $this->out( $e->getMessage());
            echo 'Exception : ', $e->getMessage(), "\n";
            return false;
        }
    }


    function sanear_string($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $noPermitidos = ['=','<','+','(','#','>','[',']','"','"','{','|','}','$',')','‚','$','@',',',']','Ó','^','_',
            '„','…','%','`','ˆ','‹','','(','!','%','.',':',';','?','&','\'','[',',','*','•','Í',
            'Ñ','°','–','¡','»','¿','÷','/','á','é','ò','ú','','','¨',str_replace('"','','"\"')];

        $string = str_replace(
            $noPermitidos,
            '',
            $string
        );

        $string = str_replace('?','',$string);

        return $string;
    }

    public function export($date = null){ /* LOG TRANSACCIONAL AUTOMATIZADO*/

        $start_time = microtime(true);
        $tiempo = ini_set('memory_limit', '-1');
        $this->out('Inicio de proceso.');
        $this->out('$tiempo '.$tiempo);

        $conditions = [];

        if(!is_null($date)) {
            $fecha = date($date);
            $fechaInicio = strtotime ( '-5 day' , strtotime ( $fecha ) ) ;
            $inicio = date ( 'Ymd' , $fechaInicio );
            $fechaInicio = date ( 'Y-m-d 00:00:00' , $fechaInicio );
            $fechaFin = date ($date . ' 23:59:59');
        }else{
            $fecha = date('Y-m-d');
            $fechaInicio = strtotime ( '-5 day' , strtotime ( $fecha ) ) ;
            $inicio = date ( 'Ymd' , $fechaInicio );
            $fechaInicio = date ( 'Y-m-d 00:00:00' , $fechaInicio );
            $fechaFin = date ('Y-m-d 23:59:59');
        }

        $this->out($fechaFin);
        $conditions['Logs.created >='] = $fechaInicio;
        $conditions['Logs.created <='] = $fechaFin;

        try {
            $file = Log::export($conditions, $date);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            exit();
        }


        $nameZip = 'logs_'.$inicio.'-a-'.date('Ymd', strtotime($fecha)).'.zip';

        $this->out($file);

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $comando = 'cd '.TMP.'files/ && powershell Compress-Archive '. $file .' '.$nameZip.' && move '.$nameZip.' logs/';
        } else {
            $comando = 'cd '.TMP.'files/ && zip '.$nameZip.' '. $file .' && mv '.$nameZip.' logs';
        }


        $this->out($comando);
    
        exec($comando);
        $this->loadModel('LogsFiles');
        $logsFile = $this->LogsFiles->newEntity();

            $data = [
              'name_file' => $nameZip,
              'file_dir' => TMP.'files/logs/',
            ];

            $logsFile = $this->LogsFiles->patchEntity($logsFile, $data);
            if ($this->LogsFiles->save($logsFile)) {
                $this->out('Registro creado.');
            } else {
                $this->out('Error creando registro');
            }


        $this->out('Fin del proceso.');
        
        //return true; /* COMENTAR PARA ACTIVAR EL ENVIO DE CORREO*/
        
        $end_time = microtime(true);
        $duration = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        $duration = ($end_time) - ($start_time);
        $hours = (int)((($duration)/60)/60);
        $minutes = (int)(($duration)/60) - ($hours) * 60;
        $seconds = (int)($duration) - ($hours) * 60 * 60 - $minutes * 60;
        $filePath = TMP . 'files/' . $file; 

        $message = '<p>Buen día ,</p><br><br><p>Informo que ya fue cargado el Log de la fecha '.$fechaInicio. ' hasta la fecha ' .$fechaFin. '. </p><br> Tiempo empleado para la ejecución de este proceso fue : <strong> '.$hours.' horas, '.$minutes.' minutos y '.$seconds.' segundos.</strong> <br> Gracias';
        
        try {
            $this->out('Enviando correo...');
            $email = new Email();

            $settings = Cache::read('settings', 'long');

            $fromName = $settings['Email']['from_name'];
            $from = $settings['Email']['from_email'];
            $port = $settings['Email']['port'];
            $host = $settings['Email']['host'];
            $user = $settings['Email']['user'];
            $password = $settings['Email']['password'];

            $email::setConfigTransport('email', [
                'host' => $host,
                'port' => $port,
                'username' => $user,
                'password' => $password,
                'className' => 'Smtp',
                'tls' => true
            ]);

            $email->setTransport('email');
            $email->setEmailFormat('html');
            $email->setFrom([$from => $fromName]);
            $email->setLayout('');
            $email->setTemplate('message');
            $email->setViewVars([
                'message' => $message
            ]);

            $email->setSubject('LOG Davinegociador ' . $date);
            
            $gestionEmails = [];
            foreach (explode(";", $settings['EnvioLog']['log_email_lista']) as $emailTo) {
                $gestionEmails[$emailTo] = 'GestionLog';
            };

            $email->addTo($gestionEmails);
            $email->send();
            $this->out('Fin de proceso.');
            return true;
        } catch (Exception $e) {
            $this->out('Error enviando correo...');
            echo 'Exception : ', $e->getMessage(), "\n";
            return false;
        }

    }

    public function checkInactiveUsers() {
        
        $this->loadModel('Roles');
        $roles = $this->Roles->find('all')->all();
        $roles = json_decode(json_encode($roles), true);

        $this->loadModel('Users');
        $settings = Cache::read('settings', 'long');
        $date = date('Y-m-d');

        //Verify Inactive Users
        $verifyDays = $settings['Clientes']['limit_days_to_disable'];
        $dateLastLogin = strtotime('-' . $verifyDays . ' days', strtotime($date));
        $dateLessDays = date('Y-m-d 23:59:59', $dateLastLogin);

        $this->out('Escribiendo archivo - Usuarios Inactivos...');
        $nameFileInactive = 'UsuariosInactivos_'. date('Y-m-d_H-i-s') . '.csv';
        $filePathInactive = TMP . 'files/' . $nameFileInactive;
        $fileInactive = fopen($filePathInactive, 'w');
        fputcsv($fileInactive, [
            'id',
            'Email',
            'Identificación',
            'Nombre',
            'Número Celular',
            'Último Acceso',
            'Rol',
            'Estado Actualizado'
        ]);
        if (!empty($verifyDays) && $verifyDays > 0) {
            $inactiveCustomers = $this->Users->find()
                ->select(['id', 'email', 'identification', 'name', 'mobile', 'last_login', 'role_id'])
                ->where([
                    'last_login <' => $dateLessDays,
                    'modified <' => $dateLessDays,
                    'role_id IN' => [2, 3, 4],
                    'user_status_id' => 1,
                ])
                ->order(['id' => 'ASC'])
                ->all();
    
            foreach ($inactiveCustomers as $row) {
                $rowData = json_decode(json_encode($row), true);
                $rowData['new_status'] = 'Active';
                $rowData['role_id'] = $roles[$rowData['role_id'] - 1]['name'];
                
                $usersQuery = $this->Users->query();
                $usersQuery->update()
                    ->set(['user_status_id' => 2])
                    ->where(['id' => $rowData['id']]);
                if ($usersQuery->execute()) {
                    $rowData['new_status'] = 'Inactivated';
                }
                
                $rowData['last_login'] = date('Y-m-d H:i:s', strtotime($rowData['last_login']));
                fputcsv($fileInactive, $rowData);
            }
        } else {
            $this->out('Campo en 0 o vacío');
        }
        fclose($fileInactive);
        $this->out('Archivo Inactivos: ' . $nameFileInactive);

        //Verify Users Termination DATE
        $finishedCustomers = $this->Users->find()
            ->select(['id', 'email', 'identification', 'name', 'mobile', 'start_date', 'end_date', 'last_login', 'role_id'])
            ->where([
                'end_date <' => date('Y-m-d'),
                'role_id IN' => [2, 3, 4],
                'user_status_id' => 1,
            ])
            ->order(['id' => 'ASC'])
            ->all();

        $this->out('Escribiendo archivo - Usuarios Finalizados...');
        $nameFileFinished = 'UsuariosFinalizados_'. date('Y-m-d_H-i-s') . '.csv';
        $filePathFinished = TMP . 'files/' . $nameFileFinished;
        $fileFinished = fopen($filePathFinished, 'w');
        fputcsv($fileFinished, [
            'id',
            'Email',
            'Identificación',
            'Nombre',
            'Número Celular',
            'Fecha Inicio',
            'Fecha Fin',
            'Último Acceso',
            'Rol',
            'Estado Actualizado'
        ]);
        foreach ($finishedCustomers as $row) {
            $rowData = json_decode(json_encode($row), true);
            $rowData['new_status'] = 'Active';
            $rowData['role_id'] = $roles[$rowData['role_id'] - 1]['name'];

            $usersQuery = $this->Users->query();
            $usersQuery->update()
                ->set(['user_status_id' => 2])
                ->where(['id' => $rowData['id']]);
            if ($usersQuery->execute()) {
                $rowData['new_status'] = 'Inactive';
            }
            
            $rowData['last_login'] = date('Y-m-d H:i:s', strtotime($rowData['last_login']));
            fputcsv($fileFinished, $rowData);
        }
        fclose($fileFinished);
        $this->out('Archivo Finalizados: ' . $nameFileFinished);

        // Send Email
        try {
            $this->out('Enviando correo...');
            $email = new Email();

            $fromName = $settings['Email']['from_name'];
            $from = $settings['Email']['from_email'];
            $port = $settings['Email']['port'];
            $host = $settings['Email']['host'];
            $user = $settings['Email']['user'];
            $password = $settings['Email']['password'];

            $email::setConfigTransport('email', [
                'host' => $host,
                'port' => $port,
                'username' => $user,
                'password' => $password,
                'className' => 'Smtp',
                'tls' => true
            ]);

            $email->setTransport('email');
            $email->setEmailFormat('html');
            $email->setFrom([$from => $fromName]);
            $email->setLayout('');
            $email->setTemplate('message');
            $email->setViewVars([
                'message' => 'Buen día, Adjunto archivos relacionados a usuarios desactivados automáticamente.'
            ]);

            $email->setSubject('Usuarios Desactivados ' . date('Y-m-d'));


            $usersEmails = [];
            foreach (explode(";", $settings['EnvioGestionUsuarios']['usuarios_emails_lista']) as $emailTo) {
                $usersEmails[$emailTo] = 'Gestion Usuarios';
            };
            $email->addTo($usersEmails);

            $email->addAttachments([$nameFileInactive => $filePathInactive]);
            $email->addAttachments([$nameFileFinished => $filePathFinished]);

            $email->send();
            $this->out('Email enviado correctamente.');
            return true;
        } catch (Exception $e) {
            $this->out('Error enviando correo...');
            echo 'Exception : ', $e->getMessage(), "\n";
            return false;
        }
    }

    public function rediferido($date = null)
    {
        $start_time = microtime(true);
        $this->out('Inicio proceso...');

        if(!is_null($date)) {
            $fechaFin = date($date.' 20:50:00');
            $fechaInicio = strtotime('-24 hours', strtotime($fechaFin));
            $fechaInicio = date('Y-m-d 20:51:00', $fechaInicio);
            $nameFile = 'Rediferidos_Davinegociador'. date('Ymd', strtotime($date)) . '.csv';
        }else{
            $fechaFin = date('Y-m-d 20:50:00');
            $fechaInicio = strtotime('-24 hours', strtotime($fechaFin));
            $fechaInicio = date('Y-m-d 20:51:00', $fechaInicio);
            $nameFile = 'Rediferidos_Davinegociador'. date('Ymd') . '.csv';
        }

        $this->loadModel('AdjustedObligations');
        $this->loadModel('HistoryCustomers');
        
        $obligations = $this->AdjustedObligations->find()
            ->join([
                'HistoryCustomers' => [
                    'table' => 'history_customers',
                    'type' => 'inner',
                    'conditions' => 'AdjustedObligations.log_id = HistoryCustomers.log_id',
                ]
            ])
            ->contain(['AdjustedObligationsDetails'])
            ->where([
                'documentation_date >=' => $fechaFin,
            ])->orWhere(function ($exp) use ($fechaInicio, $fechaFin){
                return $exp->between('date_negotiation',$fechaInicio,$fechaFin);
            })
        ->all();
        
        $filePath = TMP . 'files/' . $nameFile;
        $fp2 = fopen($filePath, 'w');
        $data = [];
            /** @var  $obligation AdjustedObligation */
    
        foreach ($obligations as $obligation) {
            $historys = $this->HistoryCustomers->find()
            ->where([
                'log_id' => $obligation->log_id,
            ])
            ->all();
            foreach ($historys as $history){
                foreach ($obligation->adjusted_obligations_details as $detail) {
                    if($detail->type_strategy == 3){
                        if($detail->retrenched_policy == 3){
                            $negociacion = Obligation::getCogigo($detail->type_strategy,0);
                        } else {
                            $negociacion = Obligation::getCogigo($detail->type_strategy);
                        }
                        $fields = [
                            'type_identification' =>  str_pad($obligation->type_identification, 2, '0', STR_PAD_LEFT),
                            'identification' => str_pad($obligation->identification, 16, '0', STR_PAD_LEFT),
                            'obligation' => $detail->obligation,
                            'origen' => $detail->origin,
                            'fecha_compromiso' => (!empty($obligation->documentation_date))?$obligation->documentation_date->format('Ymd'): '0',
                            'pago_real' => ($detail->payment_agreed)?$detail->payment_agreed:0,
                            'plazo' => $detail->months_term,
                            'codigo_estrategia' => '',
                            'tipo_rediferido' => $negociacion,
                            'cliente_desiste' => ($history->customer_desist)?'Si':'NO',
                            'reversan' => '',
                            'sequential_obligation' => $detail->sequential_obligation
                        ];
                        $data[] = $fields;
                    }
                        
                }
            }
        }

        $this->out('Escribiendo archivo...');
        foreach ($data as $file) {
            foreach ($file as $key => $col) {
                if ($key != 'hora_generacion') {
                    if (!fwrite($fp2, $col . ';')) {
                        echo 'error 1 ' . $key;
                    }
                } else {
                    if (!fwrite($fp2, $col)) {

                    }
                }
            }
            fwrite($fp2, '' . PHP_EOL);
        }
        fclose($fp2);
        //return true;
        $end_time = microtime(true);
        $duration = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        $duration = ($end_time) - ($start_time);
        $hours = (int)((($duration)/60)/60);
        $minutes = (int)(($duration)/60) - ($hours) * 60;
        $seconds = (int)($duration) - ($hours) * 60 * 60 - $minutes * 60;
        $message = '<p>Buen día ,</p><br><br><p>Informo que ya fue ejecutado los rediferidos de la fecha '.$fechaInicio. ' hasta la fecha ' .$fechaFin. '. </p><br> Tiempo empleado para la ejecución de este proceso fue : <strong> '.$hours.' horas, '.$minutes.' minutos y '.$seconds.' segundos.</strong> <br> Gracias';

        try {
            $this->out('Enviando correo...');
            $email = new Email();

            $settings = Cache::read('settings', 'long');

            $fromName = $settings['Email']['from_name'];
            $from = $settings['Email']['from_email'];
            $port = $settings['Email']['port'];
            $host = $settings['Email']['host'];
            $user = $settings['Email']['user'];
            $password = $settings['Email']['password'];

            $email::setConfigTransport('email', [
                'host' => $host,
                'port' => $port,
                'username' => $user,
                'password' => $password,
                'className' => 'Smtp',
                'tls' => true
            ]);

            $email->setTransport('email');
            $email->setEmailFormat('html');
            $email->setFrom([$from => $fromName]);
            $email->setLayout('');
            $email->setTemplate('message');
            $email->setViewVars([
                'message' => $message
            ]);

            $nameFile = explode('/', $filePath);
            $nameFile = end($nameFile);

            $email->setSubject('Gestión Rediferido ' . $date);
            
            $gestionEmails = [];
            foreach (explode(";", $settings['EnvioGestion']['gestion_emails_lista']) as $emailTo) {
                $gestionEmails[$emailTo] = 'Gestion';
            };

            $email->addTo($gestionEmails);
            $email->addAttachments([$nameFile => $filePath]);
            $email->send();
            $this->out('Fin de proceso.');
            return true;
        } catch (Exception $e) {
            $this->out('Error enviando correo...');
            echo 'Exception : ', $e->getMessage(), "\n";
            return false;
        }
    }

}