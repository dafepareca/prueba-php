<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;
use App\Model\Table\ConditionsTable;
use App\Model\Table\TypeObligationsTable;
use App\Model\Table\TypesConditionsTable;
use App\Model\Table\ObligationsTable;
use App\Model\Entity\Obligation;
use App\Model\Table\HistoryStatusesTable;
use Cake\Network\Request;
use Cake\Database\Exception;
use App\Controller\LogTransaccionController;

/**
 * LogCommercial Entity
 *
 * @property int $id
 * @property int $log_id
 * @property string $accion
 * @property string $type_identification
 * @property int $identification
 * @property \Cake\I18n\FrozenTime $fecha
 * @property \Cake\I18n\FrozenTime $hora
 * @property string $obligation
 * @property string $telefono
 * @property string $extension
 * @property string $ciudad
 * @property string $tipo_telefono
 * @property string $tipo_direccion
 * @property string $customer_email
 * @property string $codigo_gestor
 * @property string $codigo_recuperador
 * @property string $tipo_resultado
 * @property string $contacto
 * @property string $motivo_no_pago
 * @property string $nivel_ingresos
 * @property string $negociacion
 * @property \Cake\I18n\FrozenTime $fecha_2
 * @property \Cake\I18n\FrozenTime $hora_2
 * @property \Cake\I18n\FrozenTime $fecha_documentacion
 * @property \Cake\I18n\FrozenDate $fecha_pago
 * @property string $valor_negociacion
 * @property string $codigo_reporte
 * @property \Cake\I18n\FrozenTime $fecha_reporte
 * @property \Cake\I18n\FrozenTime $hora_reporte
 * @property string $tarea
 * @property \Cake\I18n\FrozenTime $fecha_tarea_desde
 * @property \Cake\I18n\FrozenTime $fecha_tarea_hasta
 * @property \Cake\I18n\FrozenTime $hora_tarea_desde
 * @property \Cake\I18n\FrozenTime $hora_tarea_hasta
 * @property string $comentario
 * @property string $comentario_terceros
 * @property string $consecutivo
 * @property string $consecutivo_relativo
 * @property \Cake\I18n\FrozenTime $fecha_generacion
 * @property \Cake\I18n\FrozenTime $hora_generacion
 * @property int $estado
 * @property \Cake\I18n\FrozenTime $fecha_acceso_app
 * @property string $motivo_pago_descrip
 * @property int $aprobación_tyc
 * @property string $id_paquete_documental
 * @property \Cake\I18n\FrozenTime $fecha_agendamiento
 * @property string $motivo_agendamiento
 * @property string $ip
 * @property string $id_session_canal
 *
 * @property \App\Model\Entity\Log $log
 */
class LogCommercial extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    public static function PersistenceLogsCommercial($historyCustomer){
        
        try {
            $adjusted_obligations = TableRegistry::get('adjusted_obligations');
            $history = TableRegistry::get('history_customers');
            $history_details = TableRegistry::get('history_details');
            $adjusted_obligations_details = TableRegistry::get('adjusted_obligations_details');

            $history = $history->get($historyCustomer->id);
            if(!empty($history)){
                $history_details = $history_details->find()->where(['history_customer_id' => $history->id])->all();
            }
            
            $adjusted_obligations = $adjusted_obligations->find()
            ->where([
                'log_id' => $history->log_id,
                'pending_committee' => 0
                ])
            ->order(['log_id' => 'DESC'])
            ->first();
            $idLogL = $history->log_id;
            if(!empty($adjusted_obligations)){
             
                $adjusted_obligations_details = $adjusted_obligations_details->find()
                ->where(['adjusted_obligation_id' => $adjusted_obligations->id])
                ->all();
                
            }

            $logCommercialTable = TableRegistry::get('log_commercial');
            $logCommercial = $logCommercialTable->newEntity();
            
            $arrayLogs = [];

            foreach ($adjusted_obligations_details as $detail) {
                $necesitaPagareUnificacion = 1;
                $necesitaPagareACPK = 1;
                foreach ($history_details as $hDetail){
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
                $negociacion = Obligation::getCogigo($detail->type_strategy);

                $pagoInicial = (is_null($adjusted_obligations->payment_agreed))?$detail->payment_agreed:$adjusted_obligations->payment_agreed;
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

                if($adjusted_obligations->approved_committee){
                    $tipoResultado = 'CA';
                }
                if (!empty($adjusted_obligations->coordinator_id) AND $adjusted_obligations->approved_committee == 0) {
                    $comentario = $adjusted_obligations->reason_rejection;
                    $tipoResultado = 'CN';
                } elseif (in_array($detail->type_strategy, [6, 7, 8])) {
                    $comentario = $detail->strategy . ', con un valor a pagar de  ' . number_format($detail->new_fee);
                } elseif (in_array($detail->type_strategy, [12])) {
                    $comentario = $detail->strategy . ', condonacion inicial  de  ' . ($adjusted_obligations->initial_condonation * 100) . '% por valor de ' . number_format($adjusted_obligations->value_initial_condonation) .
                        ', condonacion final de  ' . ($adjusted_obligations->end_condonation * 100) . '% por valor de ' . $adjusted_obligations->value_end_condonation . ', pago inicial de ' . number_format($adjusted_obligations->initial_payment) . ' plazo de ' . $detail->months_term . ' meses y cuota proyectada de ' . number_format($detail->new_fee);
                    $pagoInicial = $adjusted_obligations->initial_payment;
                } elseif (in_array($detail->type_strategy, [13])) {
                    $comentario = $detail->strategy . ', Tasa efectiva anual ' . $detail->annual_effective_rate . '%,  Tasa Nominal ' . $detail->monthly_rate . '%, con un unico pago de por valor de  ' . number_format($detail->new_fee);
                    if(empty($pagoInicial)){
                        $pagoInicial = $detail->new_fee;
                    }
                } elseif ($detail->type_strategy == 2 && $detail->currency == 'UVR'){
                    $uvrRate = ObligationsTable::annualEffectiveRateUvr();
                    $tasaAnual = $uvrRate['rate'];
                    $tasaMensual = pow((1 + ($tasaAnual / 100)), (1 / 12)) - 1;
                    $tasaMensual = @round($tasaMensual * 100, 2); 
                    $comentario = $detail->strategy . ', Tasa efectiva anual ' . $tasaAnual . '%,  Tasa Nominal ' . $tasaMensual . '%, con un plazo de ' . $detail->months_term . ' meses y cuota proyectada de ' . number_format($detail->new_fee);      
                } else {
                    $comentario = $detail->strategy . ', Tasa efectiva anual ' . $detail->annual_effective_rate . '%,  Tasa Nominal ' . $detail->monthly_rate . '%, con un plazo de ' . $detail->months_term . ' meses y cuota proyectada de ' . number_format($detail->new_fee);
                }

                 if(empty($pagoInicial)){
                     $pagoInicial = 1;
                 }

                $comentario = LogCommercial::sanear_string($comentario);

                $logCommercial->log_id = $adjusted_obligations->log_id;
                $logCommercial->accion = 'INFC';
                $logCommercial->type_identification = $adjusted_obligations->type_identification;
                $logCommercial->identification = $adjusted_obligations->identification;
                $logCommercial->fecha = $adjusted_obligations->date_negotiation;
                $logCommercial->hora = $adjusted_obligations->date_negotiation;
                $logCommercial->obligation = $numeroObligacion;
                $logCommercial->telefono = null;
                $logCommercial->extension = null;
                $logCommercial->ciudad = null;
                $logCommercial->tipo_telefono = null;
                $logCommercial->tipo_direccion = (!empty($adjusted_obligations->customer_email)) ? '06' : '00';
                $logCommercial->customer_email = $adjusted_obligations->customer_email;
                $logCommercial->codigo_gestor = $adjusted_obligations->code_manager;
                $logCommercial->codigo_recuperador = $adjusted_obligations->company_code;
                $logCommercial->tipo_resultado = $tipoResultado;
                $logCommercial->contacto = null;
                $logCommercial->motivo_no_pago = null;
                $logCommercial->nivel_ingresos = null;
                $logCommercial->negociacion = $negociacion;
                $logCommercial->fecha_2 = $adjusted_obligations->date_negotiation;
                $logCommercial->hora_2 = $adjusted_obligations->date_negotiation;
                $logCommercial->fecha_documentacion = $adjusted_obligations->documentation_date;
                $logCommercial->fecha_pago = $adjusted_obligations->documentation_date;
                $logCommercial->valor_negociacion = $pagoInicial;
                $logCommercial->codigo_reporte = null;
                $logCommercial->fecha_reporte = null;
                $logCommercial->hora_reporte = null;
                $logCommercial->tarea = null;
                $logCommercial->fecha_tarea_desde = null;
                $logCommercial->fecha_tarea_hasta = null;
                $logCommercial->hora_tarea_desde = null;
                $logCommercial->hora_tarea_hasta = null;
                $logCommercial->comentario = $comentario;
                $logCommercial->comentario_terceros  = null;
                $logCommercial->consecutivo  = null;
                $logCommercial->consecutivo_relativo  = null;
                $logCommercial->fecha_generacion = null;
                $logCommercial->hora_generacion = null;
                $logCommercial->estado = null;
                $logCommercial->fecha_acceso_app = null;
                $logCommercial->motivo_pago_descrip = null;
                $logCommercial->aprobación_tyc = null;
                $logCommercial->id_paquete_documental = null;
                $logCommercial->fecha_agendamiento = null;
                $logCommercial->motivo_agendamiento = null;
                $logCommercial->ip = null;
                $logCommercial->id_session_canal = null;
                $logCommercial->sequential_obligation = $detail->sequential_obligation;
                
                // $fields = [
                //     // 'credit_payment_day' => "  ",
                //     // 'documents_required' => " ",
                //     // 'is_uvr' => $detail->currency == 'UVR' ? "1" : " "
                // ];

                if ($detail->type_strategy == 1) {
                    $logCommercial->negociacion = Obligation::getCogigo($detail->type_strategy, $necesitaPagareUnificacion);
                }

                if ($detail->type_strategy == 12) {
                    $logCommercial->negociacion = Obligation::getCogigo($detail->type_strategy, $necesitaPagareACPK);
                }

                if($detail->type_strategy == 3){
                    if($detail->retrenched_policy == 3){
                        $logCommercial->negociacion = Obligation::getCogigo(3, 0);
                    }
                }
                $timeInicio = microtime(true);
                array_push($arrayLogs, $logCommercial);
                $logCommercial = $logCommercialTable->newEntity();
            }
            try {
                foreach($arrayLogs as $logCommercial){
                    $result = $logCommercialTable->save($logCommercial);
                    if ($result == null || $result == 0 || $result < 0) {
                        throw new Exception(
                            "Se genero error al momento de realizar transacción de la informacion a la tabla log comercial."
                        );
                    }
                }
                $timeFinal = microtime(true);
                $duracion = ($timeFinal) - ($timeInicio);
                $logTran = LogTransaccionController::EscribirTiempo('Finalizar','Insertar logCommercial','LogCommercial','PersistenceLogsCommercial',$idLogL,$duracion);
                
            } catch (Exception $th) {
                throw new Exception ("Error en el metdo de guardar el log de Comercial",0,$th);
            }
        } catch (Exception $th) {
            throw new Exception ("Error en el metdo de generar el log de comercial",0,$th);
        }
        
   
    }
    
     static function sanear_string($string)
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

}
