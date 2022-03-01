<?php
namespace App\Model\Entity;

use Cake\Cache\Cache;
use Cake\ORM\Entity;
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
 * LogTransactional Entity
 *
 * @property int $id
 * @property int $log_id
 * @property \Cake\I18n\FrozenTime $fecha
 * @property string $type_identification
 * @property int $codigo
 * @property int $identification
 * @property string $estado
 * @property string $hora
 * @property string $obligation
 * @property float $customer_revenue
 * @property float $customer_paid_capacity
 * @property float $initial_payment_punished
 * @property string $customer_email
 * @property float $total_debt
 * @property float $previous_minimum_payment
 * @property float $initial_fee
 * @property string $strategy
 * @property string $code_strategy
 * @property string $cuota_Proyctada
 * @property int $months_term
 * @property float $annual_effective_rate
 * @property float $nominal_rate
 * @property string $user_dataweb
 * @property float $payment_agreed
 * @property \Cake\I18n\FrozenTime $documentation_date
 * @property string $office_name
 * @property \Cake\I18n\FrozenTime $documentation_date_2
 * @property string $empresa
 * @property string $alternativa
 * @property bool $aprobado_por_comite
 * @property string $coordinador
 * @property string $reason_rejection
 * @property float $initial_condonation
 * @property float $value_initial_condonation
 * @property float $end_condonation
 * @property float $value_end_condonation
 * @property float $pago_total_vehiculo
 * @property float $pago_total_vehiculo_experto
 * @property float $oferta_cliente_vehiculo
 * @property \Cake\I18n\FrozenTime $date_valorization
 * @property string $type_valorization
 * @property float $value_valorization
 * @property float $value_parking
 * @property float $value_subpoena
 * @property float $value_taxes
 * @property float $value_others
 * @property int $cnd
 * @property bool $documentos
 * @property string $user_documents_delivered
 * @property \Cake\I18n\FrozenTime $documentos_fecha
 * @property bool $desiste
 * @property string $user_customer_desist
 * @property \Cake\I18n\FrozenTime $desiste_fecha
 * @property int $credit_payment_day
 * @property bool $documents_required
 * @property string $is_uvr
 * @property string $campania
 * @property string $observaciones
 * @property \Cake\I18n\FrozenTime $fecha_aceso_app
 * @property string $motivo_no_pago
 * @property string $celular
 * @property int $aprovacion_TyC
 * @property string $id_paquete_documental
 * @property \Cake\I18n\FrozenTime $fecha_agendamiento
 * @property string $motivo_agendamiento
 * @property string $ip
 * @property string $id_sesion_canal
 * @property int $estado_log
 *
 * @property \App\Model\Entity\Log $log
 */
class LogTransactional extends Entity
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

    public static function PersistenceLogsTransactional($historyCustomer){

       try {
        $condiciones = Cache::read('conditions', 'long');
        $log = TableRegistry::get('Logs');
        
        $user = TableRegistry::get('Users');
        $customer = TableRegistry::get('customers');
        $obligation_table = TableRegistry::get('obligations');
        $customer_type_identification = TableRegistry::get('customer_type_identifications');
        $business = TableRegistry::get('business');
        $adjusted_obligations = TableRegistry::get('adjusted_obligations');
        $adjusted_obligations_details = TableRegistry::get('adjusted_obligations_details');
        $history = TableRegistry::get('history_customers');
        $history_details = TableRegistry::get('history_details');
        $history_payment_vehicles = TableRegistry::get('history_payment_vehicles');
        $history_normalizations = TableRegistry::get('history_normalizations');
        $history_punished_portfolios = TableRegistry::get('history_punished_portfolios');
        $history_status = TableRegistry::get('history_statuses');
        
        
        $log = $log->get($historyCustomer->log_id);
        $user = $user->get($log->user_id);
        $business = $business->get($user->busines_id);
        $customer = $customer->find()
        ->where(['id' => $historyCustomer->customer_id])
        ->order(['charge_id' => 'DESC'])
        ->first();
        $customer_type_identification = $customer_type_identification->get($historyCustomer->type_identification_id);
        $history_status = $history_status->get($historyCustomer->history_status_id);
        
        $adjusted_obligations = $adjusted_obligations->find()
        ->where(['log_id' => $log->id])
        ->order(['log_id' => 'DESC'])
        ->first();
        
        if(!empty($adjusted_obligations)){
            
            $adjusted_obligations_details = $adjusted_obligations_details->find()->where(['adjusted_obligation_id' => $adjusted_obligations->id])->all();
        }
        $history = $history->get($historyCustomer->id);
        if(!empty($history)){
            
            $history_details = $history_details->find()->where(['history_customer_id' => $history->id])->all();
            $history_payment_vehicles = $history_payment_vehicles->find()->where(['history_customer_id' => $history->id])->first();
            $history_normalizations = $history_normalizations->find()->where(['history_customer_id' => $history->id])->all();
            $history_punished_portfolios = $history_punished_portfolios->find()->where(['history_customer_id' => $history->id])->all();
        }

        $logTransactionalTable = TableRegistry::get('log_transactional');
        $logTransactional = $logTransactionalTable->newEntity();  
        
        $fechaDocumentacion = null;
        $nombreOficina = null;
        $nombreCoordinador = null;
        $condonacionInicial = null;
        $valueCondonacionInicial = null;
        $endCondonation = null;
        $valueEndCondonation = null;
        $paymentAgreed = null;
        $creditPaymentDay = null;
        $documentsRequired = null;
        /*CAMPOS NUEVOS*/
        $consecutivo_cliente = $history->sequential_customer;
        /*CAMPOS NUEVOS*/
        //APP
        $customerTelephone = '';            
        
        if(!empty($adjusted_obligations)){
                if($adjusted_obligations->log_id == $history->log_id){
                    $fechaDocumentacion = (!empty($adjusted_obligations->documentation_date))?$adjusted_obligations->documentation_date:null;
                    $nombreOficina = $adjusted_obligations->office_name;
                    $nombreCoordinador = $adjusted_obligations->coordinator_name;
                    $condonacionInicial = $adjusted_obligations->initial_condonation;
                    $valueCondonacionInicial = $adjusted_obligations->value_initial_condonation;
                    $endCondonation = $adjusted_obligations->end_condonation;
                    $valueEndCondonation = $adjusted_obligations->value_end_condonation;
                    $paymentAgreed = $adjusted_obligations->payment_agreed;
                    $creditPaymentDay = $adjusted_obligations->credit_payment_day;
                    $documentsRequired = $adjusted_obligations->documents_required;
                    $customerTelephone = $adjusted_obligations->customer_telephone;
                }
        }
       
        if(empty($paymentAgreed)){
            
            $paymentAgreed = $history->payment_agreed;
        }

        $saldoNor = 0;
        $codigo = null;
        
        if(!empty($history_details)){
            $necesitaPagareUnificacion = 1;
            $necesitaPagareACPK = 1;
            foreach ($history_details as $detail) {
                if($detail->type_strategy == 1){
                    $saldoNor+=$detail->total_debt;
                    if($detail->pagare == 0 || $detail->pagare == null){
                        $necesitaPagareUnificacion = 0;
                    }
                }
                if($detail->type_strategy == 12){
                    if($detail->pagare == 0 || $detail->pagare == null){
                        $necesitaPagareACPK = 0;
                    }
                } 
            }
            
            if ($saldoNor > 0) {
                
                $condicionesNOREXP = $condiciones[TypesConditionsTable::NORMALIZACIONEXPRES];
                $resultado = ConditionsTable::getValorCondicion($condicionesNOREXP, $saldoNor);

                $codigo = $resultado;
            }
            $arrayLogs = [];
            $consecutivo_obligacion = null;
            foreach ($history_details as $detail){                

                $consecutivo_obligacion = $detail->sequential_obligation;
                
                $razonrechazo = $history->reason_rejection != '' ? $history->reason_rejection : null;
                
                
                $ofertaVehiculo = null;
                
                if (!empty($history_payment_vehicles)){
                    $ofertaVehiculo = $history_payment_vehicles;
                }
                
                $tiempo = $detail->term;
                $nuevaCuota = $detail->new_fee;
                $cuota = $detail->fee;
                $tasaMensual = $detail->rate_em;
                $tasaAnual = $detail->rate_ea;

                if($detail->type_strategy == 2 && $detail->currency == 'UVR'){                    
                    $tasaMensual = pow((1 + ($tasaAnual / 100)), (1 / 12)) - 1;
                    $tasaMensual = @round($tasaMensual * 100, 2);       
                }

                if($detail->type_strategy == 1){
                    if(!empty($history_normalizations)) {
                        foreach ($history_normalizations as $normalizacion) {
                            if ($normalizacion->selected == 1) {
                                $tiempo = $normalizacion->term;
                                $nuevaCuota = ceil($normalizacion->fee);
                                $tasaMensual = $normalizacion->rate;
                                $tasaAnual = round(((pow(1 + ($tasaMensual/100), 12)) - 1) * 100, 2);
                            }
                        }
                    }
                }

                if($detail->type_strategy == 12){

                    if(!empty($history_punished_portfolios)) {

                        foreach ($history_punished_portfolios as $normalizacionP) {

                            if ($normalizacionP->selected == 1) {

                                $tiempo = $normalizacionP->term;
                                $nuevaCuota = $normalizacionP->fee;
                                $tasaAnual = $normalizacionP->rate;
                                $tasaMensual = pow((1 + ($tasaAnual / 100)), (1 / 12)) - 1;
                                $tasaMensual = @round($tasaMensual * 100, 2);
                            }
                        }
                        
                        $condicionesACPKEXPRES = $condiciones[TypesConditionsTable::ACPKEXPRES];
                        $resultado = ConditionsTable::getValorCondicion($condicionesACPKEXPRES, $tiempo);

                        $codigo = $resultado;
                    }
                }

                //campos log_tranactional    
                $logTransactional->log_id = $log->id;
                $logTransactional->fecha = $history->created;
                $logTransactional->type_identification = $customer_type_identification->type;
                $logTransactional->codigo = $customer_type_identification->id;
                $logTransactional->identification = $log->customer_identification;
                $logTransactional->estado = $history_status->name;
                $logTransactional->hora = $history->created->format('H:i:s');
                $logTransactional->obligation = $detail->obligation;
                $logTransactional->customer_revenue = $history->income;
                $logTransactional->customer_paid_capacity = $history->payment_capacity;
                $logTransactional->initial_payment_punished = $history->initial_payment_punished;
                $logTransactional->customer_email = $history->customer_email;
                $logTransactional->total_debt = $detail->total_debt;
                $logTransactional->previous_minimum_payment = $detail->minimum_payment;
                $logTransactional->initial_fee = $cuota;
                $logTransactional->strategy = $detail->strategy;
                $logTransactional->code_strategy = (!is_null($codigo) && $detail->type_strategy == 1)?$codigo:Obligation::getCogigo($detail->type_strategy);
                $logTransactional->cuota_Proyctada = $nuevaCuota;
                $logTransactional->months_term = $tiempo;
                $logTransactional->annual_effective_rate = $tasaAnual;
                $logTransactional->nominal_rate = $tasaMensual;
                $logTransactional->user_dataweb = $user->name;
                $logTransactional->payment_agreed = $paymentAgreed;
                $logTransactional->documentation_date = $fechaDocumentacion;
                $logTransactional->office_name = $nombreOficina;
                $logTransactional->documentation_date_2 = $fechaDocumentacion;
                $logTransactional->empresa = (!empty($business)) ? h($business->name) : null;
                $logTransactional->alternativa = $history->alternative;
                $logTransactional->aprobado_por_comite = ($history->history_status_id == HistoryStatusesTable::ACEPTADA_COMITE)? true: false;
                $logTransactional->coordinador = $nombreCoordinador;
                $logTransactional->reason_rejection = $razonrechazo;
                $logTransactional->initial_condonation = $condonacionInicial;
                $logTransactional->value_initial_condonation = $valueCondonacionInicial;
                $logTransactional->end_condonation = $endCondonation;
                $logTransactional->value_end_condonation = $valueEndCondonation;
                $logTransactional->pago_total_vehiculo = null;
                $logTransactional->pago_total_vehiculo_experto = null;
                $logTransactional->oferta_cliente_vehiculo = null;
                $logTransactional->date_valorization = null;
                $logTransactional->type_valorization = null;
                $logTransactional->value_valorization = null;
                $logTransactional->value_parking = null;
                $logTransactional->value_subpoena = null;
                $logTransactional->value_taxes = null;
                $logTransactional->value_others = null;
                $logTransactional->cnd = $history->cnd;
                $logTransactional->documentos = $history->documents_delivered ? true: false;
                $logTransactional->user_documents_delivered = $history->user_documents_delivered;
                $logTransactional->documentos_fecha = (!empty($history->date_documents_delivered))?$history->date_documents_delivered:null;
                $logTransactional->desiste = $history->customer_desist;
                $logTransactional->user_customer_desist = $history->user_customer_desist;
                $logTransactional->desiste_fecha = (!empty($history->date_customer_desist))?$history->date_customer_desist:null;
                $logTransactional->credit_payment_day = null;
                $logTransactional->documents_required = $documentsRequired;
                $logTransactional->is_uvr = $detail->currency == 'UVR' ? true : null;
                $logTransactional->campania = $history->customer_observations ? $history->customer_observations : null;
                $logTransactional->observaciones = $history->customer_alternatives;
                $logTransactional->fecha_aceso_app = null;
                $logTransactional->motivo_no_pago = null;
                $logTransactional->celular = null;
                $logTransactional->aprovacion_TyC = null;
                $logTransactional->id_paquete_documental = null;
                $logTransactional->fecha_agendamiento = null;
                $logTransactional->motivo_agendamiento = null;
                $logTransactional->ip = null;
                $logTransactional->id_sesion_canal = null;
                $logTransactional->estado_log = 1;

                /* CAMPOS NUEVOS */
                $logTransactional->sequential_customer = $consecutivo_cliente;
                $logTransactional->sequential_obligation = $consecutivo_obligacion;
                /* CAMPOS NUEVOS */
                //fin campos
                
                /*Cambio en Rediferir*/
                if($detail->type_strategy == 3){

                    /*Se quita ciclo forech y se agrega condional de obligacion para que guarde estado correspondiente*/
                    if($detail->retrenched_policy == 3){
                        $logTransactional->code_strategy = Obligation::getCogigo(3, 0);
                    }else {
                        $logTransactional->code_strategy = Obligation::getCogigo(3, 1);
                    }  

                }

                if ($detail->type_strategy == 1 ) {
                    $logTransactional->code_strategy = Obligation::getCogigo($detail->type_strategy, $necesitaPagareUnificacion);
                    $logTransactional->credit_payment_day = $creditPaymentDay;
                }

                if ($detail->type_strategy == 12) {
                    if ($detail->pagare !== '' && $detail->pagare !== null) {
                        $logTransactional->code_strategy = Obligation::getCogigo($detail->type_strategy, $necesitaPagareACPK);
                    }
                    
                    $logTransactional->credit_payment_day = $creditPaymentDay;
                }

                if(!is_null($ofertaVehiculo) && $detail->type_obligation_id == TypeObligationsTable::VEH){

                    $tipo = $ofertaVehiculo->type_valorization;
                    $typeAvaluo = '';
                    if($tipo == 1){
                        $typeAvaluo = "Perito";
                    }elseif ($tipo == 2){
                        $typeAvaluo = "Fasecolda";
                    }elseif ($tipo == 3){
                        $typeAvaluo = "Revista motor";
                    }

                    $logTransactional->pago_total_vehiculo = $ofertaVehiculo->total_payment;
                    $logTransactional->pago_total_vehiculo_experto = $ofertaVehiculo->total_payment_expert;
                    $logTransactional->oferta_cliente_vehiculo = $ofertaVehiculo->customer_offer;

                    $logTransactional->date_valorization = (!empty($ofertaVehiculo->date_valorization))?$ofertaVehiculo->date_valorization:null;
                    $logTransactional->type_valorization = $typeAvaluo;
                    $logTransactional->value_valorization = $ofertaVehiculo->value_valorization;
                    $logTransactional->value_parking = $ofertaVehiculo->value_parking;
                    $logTransactional->value_subpoena = $ofertaVehiculo->value_subpoena;
                    $logTransactional->value_taxes = $ofertaVehiculo->value_taxes;
                    $logTransactional->value_others = $ofertaVehiculo->value_others;
                }
                
                array_push($arrayLogs, $logTransactional);
                $logTransactional = $logTransactionalTable->newEntity();
            }
            try {
                $timeInicioConsulta = microtime(true);
                $logTransactionalTableGetCount = $logTransactionalTable->find()->where(['log_id' => $log->id])->count();
                $timeFinalConsulta = microtime(true);
                $duracionConsulta = ($timeFinalConsulta) - ($timeInicioConsulta);
                $logTran = LogTransaccionController::EscribirTiempo('Declinada','Conteo logTransactional','logTransactional','PersistenceLogsTransactional',$log->id,$duracionConsulta);
                if ($logTransactionalTableGetCount) {
                    $timeInicioDelete = microtime(true);
                    $logTransactionalDelete = $logTransactionalTable->deleteAll(['log_id' => $log->id]);
                    $timeFinalDelete = microtime(true);
                    $duracionDelete = ($timeFinalConsulta) - ($timeInicioConsulta);
                    $logTran = LogTransaccionController::EscribirTiempo('Declinada','Eliminar logTransactional','logTransactional','PersistenceLogsTransactional',$log->id,$duracionDelete);
                    if ($logTransactionalDelete == null || $logTransactionalDelete == 0 || $logTransactionalDelete < 0) {
                        throw new Exception(
                            "Se genero error al momento de realizar transacción de la informacion a la tabla logTransactional (D) en la funcion PersistenceLogsTransactional controller LogTransactional."
                        );
                    }
                }
                $timeInicioSave = microtime(true);
                foreach($arrayLogs as $logTransactional){
                    $result = $logTransactionalTable->save($logTransactional);
                    if ($result == null || $result == 0 || $result < 0) {
                        throw new Exception(
                            "Se genero error al momento de realizar transacción de la informacion a la tabla logTransactional (C) en la funcion PersistenceLogsTransactional controller LogTransactional."
                        );
                    }
                }
                $timeFinalSave = microtime(true);
                $duracionSave = ($timeFinalSave) - ($timeInicioSave);
                $logTran = LogTransaccionController::EscribirTiempo('Declinada','Insertar logTransactional','logTransactional','PersistenceLogsTransactional',$log->id,$duracionSave);

            } catch (Exception $th) {
                throw new Exception ("Error en el metdo de guardar el log de transacciones",0,$th);
            }
        }
       } catch (Exception $th) {
        throw new Exception ("Error en el metdo de crear el log de transacciones",0,$th);
       }      
    }
    public static function ClienteDesiste($arrayDesiste){
        try {
            $logTransactionalTable = TableRegistry::get('log_transactional');
            $historyCustomerTable = TableRegistry::get('history_customers');
            $historyCustomer = $historyCustomerTable->get($arrayDesiste['id']);
            $timeInicioClienteDesisConteo = microtime(true);
            $logTransactionalTableGetCount = $logTransactionalTable->find()->where(['log_id' => $historyCustomer->log_id])->count();
            $timeFinalClienteDesisConteo = microtime(true);
            $duracion = ($timeFinalClienteDesisConteo) - ($timeInicioClienteDesisConteo);
            $logTran = LogTransaccionController::EscribirTiempo('Conteo','Actualización logTransactional','logTransactional','ClienteDesiste',$historyCustomer->log_id,$duracion);
            if ($logTransactionalTableGetCount > 0) {
                $timeInicioClienteDesisUpdate = microtime(true);
                $result = $logTransactionalTable->updateAll(
                    [
                        'estado' => $arrayDesiste['estado'],
                        'desiste' => $arrayDesiste['customer_desist'],
                        'user_customer_desist' => $arrayDesiste['user_customer_desist'],
                        'desiste_fecha' => $arrayDesiste['date_customer_desist'],
                        'reason_rejection' => $arrayDesiste['reason_rejection'],
                    ],
                    ['log_id' => $historyCustomer->log_id]
                );
                $timeFinalClienteDesisUpdate = microtime(true);
                $duracion = ($timeFinalClienteDesisUpdate) - ($timeInicioClienteDesisUpdate);
                $logTran = LogTransaccionController::EscribirTiempo('Actualizacion','Actualización logTransactional','logTransactional','ClienteDesiste',$historyCustomer->log_id,$duracion);
                if ($result == null){
                    throw new Exception("Se presento un error al momento de actualizar el log de transacciones en función cliente desiste");
                }
            }

            
            $mensaje = "Finaliza la función de cliente desiste de negociación";
            $parametros = [
                'idHistory' => $historyCustomer->id,
                'idLog' => $historyCustomer->log_id,
                'customer' => $historyCustomer->customer_id,
                'capacidad_pago' => $historyCustomer->payment_capacity
            ];
            $logTran = LogTransaccionController::EscribirLog($mensaje,$parametros);
        } catch (Exception $th) {
            throw new Exception("Se presento un error en la función de cliente desiste de negociación", 0, $th);
        }
        

    }

    public static function ClienteEntregaDocumentos($arrayDocumentos){
        try {
            $logTransactionalTable = TableRegistry::get('log_transactional');
            $historyCustomerTable = TableRegistry::get('history_customers');
            $historyCustomer = $historyCustomerTable->get($arrayDocumentos['id']);
            $timeInicioDocuConteo = microtime(true);
            $logTransactionalTableGetCount = $logTransactionalTable->find()->where(['log_id' => $historyCustomer->log_id])->count();
            $timeFinalDocuConteo = microtime(true);
            $duracion = ($timeFinalDocuConteo) - ($timeInicioDocuConteo);
            $logTran = LogTransaccionController::EscribirTiempo('Conteo','Actualización logTransactional','logTransactional','ClienteEntregaDocumentos',$historyCustomer->log_id,$duracion);

            if ($logTransactionalTableGetCount > 0) {
                $timeInicioDocu = microtime(true);
                $result = $logTransactionalTable->updateAll(
                    [
                        'documentos' => $arrayDocumentos['documents_delivered'],
                        'user_documents_delivered' => $arrayDocumentos['user_documents_delivered'],
                        'documentos_fecha' => $arrayDocumentos['date_documents_delivered']
                    ],
                    ['log_id' => $historyCustomer->log_id]
                );
                $timeFinalDocu = microtime(true);
                $duracion = ($timeFinalDocu) - ($timeInicioDocu);
                $logTran = LogTransaccionController::EscribirTiempo('Actualizacion','Actualización logTransactional','logTransactional','ClienteEntregaDocumentos',$historyCustomer->log_id,$duracion);
                if ($result == null || $result == 0 || $result < 0){
                    throw new Exception("Se presento un error al momento de actualizar el log de transacciones en función cliente entrega documentos");
                }
            }

            $mensaje = "Finaliza la función de cliente entrega documentos";
            $parametros = [
                'idHistory' => $historyCustomer->id,
                'idLog' => $historyCustomer->log_id,
                'customer' => $historyCustomer->customer_id,
                'capacidad_pago' => $historyCustomer->payment_capacity
            ];
            $logTran = LogTransaccionController::EscribirLog($mensaje,$parametros);
        } catch (Exception $th) {
            throw new Exception("Se presento un error en la función de cliente entrega documentos", 0, $th);
        }
    
    }

    public static function AceptarComite($arrayAceptarComite){
        try {
            $logTransactionalTable = TableRegistry::get('log_transactional');
            $historyCustomerTable = TableRegistry::get('history_customers');
            $historyCustomer = $historyCustomerTable->get($arrayAceptarComite['id']);
            $timeInicioComiteConteo= microtime(true);
            $logTransactionalTableGetCount = $logTransactionalTable->find()->where(['log_id' => $historyCustomer->log_id])->count();
            $timeFinalComiteConteo = microtime(true);
            $duracion = ($timeFinalComiteConteo) - ($timeInicioComiteConteo);
            $logTran = LogTransaccionController::EscribirTiempo('Conteo','Actualización logTransactional','logTransactional','AceptarComite',$$historyCustomer->log_id,$duracion);
            if ($logTransactionalTableGetCount > 0) {
                $timeInicio = microtime(true);
                $result = $logTransactionalTable->updateAll(
                    [
                        'estado' => $arrayAceptarComite['estado'],
                        'aprobado_por_comite' => $arrayAceptarComite['approved_committee'],
                        'coordinador' => $arrayAceptarComite['coordinator_name']
                    ],
                    ['log_id' => $historyCustomer->log_id]
                );
                $timeFinal = microtime(true);
                $duracion = ($timeFinal) - ($timeInicio);
                $logTran = LogTransaccionController::EscribirTiempo('Actualizacion','Actualización logTransactional','logTransactional','AceptarComite',$$historyCustomer->log_id,$duracion);
                if ($result == null || $result == 0 || $result < 0){
                    throw new Exception("Se presento un error al momento de actualizar el log de transacciones en función Aceptar comité");
                }
            }

            $mensaje = "Finaliza la función de Aceptar comite";
            $parametros = [
                'idHistory' => $historyCustomer->id,
                'idLog' => $historyCustomer->log_id,
                'customer' => $historyCustomer->customer_id,
                'capacidad_pago' => $historyCustomer->payment_capacity
            ];
            $logTran = LogTransaccionController::EscribirLog($mensaje,$parametros);
        } catch (Exception $th) {
            throw new Exception("Se presento un error en la función de Aceptar comité", 0, $th);
        }
    
    }

    public static function DesisteCommite($arrayDesisteComite){
        try {
            $logTransactionalTable = TableRegistry::get('log_transactional');
            $historyCustomerTable = TableRegistry::get('history_customers');
            $historyCustomer = $historyCustomerTable->get($arrayDesisteComite['id']);
            $timeInicioRechaConteo = microtime(true);
            $logTransactionalTableGetCount = $logTransactionalTable->find()->where(['log_id' => $historyCustomer->log_id])->count();
            $timeFinalRechaConteo = microtime(true);
            $duracion = ($timeFinalRechaConteo) - ($timeInicioRechaConteo);
            $logTran = LogTransaccionController::EscribirTiempo('Conteo','Actualización logTransactional','logTransactional','DesisteCommite',$historyCustomer->log_id,$duracion);

            if ($logTransactionalTableGetCount > 0) {
                $timeInicio = microtime(true);
                $result = $logTransactionalTable->updateAll(
                    [
                        'estado' => $arrayDesisteComite['estado'],
                        'aprobado_por_comite' => $arrayDesisteComite['approved_committee'],
                        'reason_rejection' => $arrayDesisteComite['reason_rejection'],
                        'coordinador' => $arrayDesisteComite['coordinator_name']
                    ],
                    ['log_id' => $historyCustomer->log_id]
                );
                $timeFinal = microtime(true);
                $duracion = ($timeFinal) - ($timeInicio);
                $logTran = LogTransaccionController::EscribirTiempo('Actualizacion','Actualización logTransactional','logTransactional','DesisteCommite',$historyCustomer->log_id,$duracion);
                if ($result == null){
                    throw new Exception("Se presento un error al momento de actualizar el log de transacciones en función Desiste comité");
                }
            }

            $mensaje = "Finaliza la función de Desiste comité";
            $parametros = [
                'idHistory' => $historyCustomer->id,
                'idLog' => $historyCustomer->log_id,
                'customer' => $historyCustomer->customer_id,
                'capacidad_pago' => $historyCustomer->payment_capacity
            ];
            $logTran = LogTransaccionController::EscribirLog($mensaje,$parametros);
        } catch (Exception $th) {
            throw new Exception("Se presento un error en la función de Desiste comité", 0, $th);
        }
    
    }

}
