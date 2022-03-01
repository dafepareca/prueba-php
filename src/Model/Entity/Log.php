<?php
namespace App\Model\Entity;

use App\Model\Table\ConditionsTable;
use App\Model\Table\HistoryStatusesTable;
use App\Model\Table\TypeObligationsTable;
use App\Model\Table\TypesConditionsTable;
use App\Model\Entity\Obligation;
use App\Model\Table\ObligationsTable;
use Cake\Cache\Cache;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Log Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $customer_type_identification_id
 * @property int $customer_identification
 * @property bool $answer
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\CustomerTypeIdentifications $customer_type_identification
 * @property \App\Model\Entity\AdjustedObligation[] $adjusted_obligations
 * @property \App\Model\Entity\HistoryCustomer[] $history_customers
 */
class Log extends Entity
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

    static function export($conditions, $date = null){ /* LOG TRANSACCIONAL*/

        if(!is_null($date)) {
            $nameFile = date($date . '_H-m-s').'-negociaciones.csv';
        }else{
            $nameFile = date('Y-m-d_H-m-s').'-negociaciones.csv';
        }
        $nameFile = 'logs_'.date('Ymd').'.csv';
        $filePath = TMP.'files/'.$nameFile;

        $logs = TableRegistry::get('Logs');
        
        $logs = $logs->find('all')
            ->contain(
                [
                    'Users' => [
                        'Business' => [
                            'fields' => ['id','name']
                        ],
                        'fields' => ['id','name']
                    ],
                    'CustomerTypeIdentifications',
                    'AdjustedObligations' => [
                        'AdjustedObligationsDetails'
                    ],
                    'HistoryCustomers' => [
                        'HistoryDetails',
                        'HistoryStatuses',
                        'HistoryPaymentVehicles',
                        'HistoryNormalizations',
                        'HistoryPunishedPortfolios',
                        'NegotiationReason'
                    ]
                ]
            )
            ->where($conditions)->all();

        $fp = fopen($filePath, 'w');
        $headers = [
            'Fecha Consulta',
            'Tipo ID',
            'Codigo ID',
            'ID',
            'Estado',
            utf8_decode(__('Obligación')),
            'Ingresos',
            'Capacidad de Pago',
            'Pago inicial castigada',
            'Email',
            'Saldo Total',
            'Pago Minimo',
            'Cuota',
            'Estrategia',
            'Codigo Estrategia',
            'Cuota Proyctada',
            'Plazo',
            'Tasa Efectiva',
            'Tasa Nominal',
            'Usuario',
            'Abono Cliente',
            'Fecha Abono Cliente',
            'Sucursal',
            utf8_decode('Fecha Documentación'),
            'Empresa',
            'Alternativa',
            'Aprobado por comite',
            'Coordinador',
            'Razon rechazo',
            utf8_decode('Condonación inicial'),
            utf8_decode('Valor condonación inicial'),
            utf8_decode('Condonación final'),
            utf8_decode('Valor condonación final'),
            utf8_decode('Pago total vehiculo'),
            utf8_decode('Pago total experto vehiculo'),
            utf8_decode('Oferta cliente vehiculo'),

            utf8_decode('Fecha avalúo'),
            utf8_decode('Tipo avalúo'),
            utf8_decode('Valor Avalúo garantia'),
            utf8_decode('Deuda parqueadero'),
            utf8_decode('Deuda comparendos'),
            utf8_decode('Deuda impuestos'),
            utf8_decode('Gastos mantenimiento'),
            utf8_decode('CND'),
            utf8_decode('Entrega documentos'),
            utf8_decode('Usuario valida documentos'),
            utf8_decode('Fecha entrega documentos'),
            utf8_decode('Cliente desiste'),
            utf8_decode('Usuario Cliente desiste'),
            utf8_decode('fecha cliente desiste'),
            utf8_decode('Día Pago Crédito'),
            utf8_decode('Documentos'),
            utf8_decode('Es UVR'),
            utf8_decode('Campaña'),
            utf8_decode('Observaciones'),
            //Campos Log
            utf8_decode('Fecha Acceso App'),
            utf8_decode('Motivo de No Pago'),
            utf8_decode('Celular'),
            utf8_decode('Aprobación TyC'),
            utf8_decode('ID Paquete documental'),
            utf8_decode('Fecha Agendamiento'),
            utf8_decode('Motivo Agendamiento'),
            utf8_decode('IP'),
            utf8_decode('Id session canal'),
            /* NUEVOS CAMPOS*/
            utf8_decode('Consecutivo Cliente'),
            utf8_decode('Consecutivo Obligacion'),
            /* NUEVOS CAMPOS*/
        ];
        fputcsv($fp, $headers);

        $condiciones = Cache::read('conditions', 'long');

        /** @var  $log Log*/
        foreach ($logs as $log) {
            /** @var   $history HistoryCustomer*/
            foreach ($log->history_customers as $history  ){

                /*CAMPOS NUEVOS*/
                $consecutivo_cliente = $history->sequential_customer;
                /*CAMPOS NUEVOS*/

                $fechaDocumentacion = '';
                $nombreOficina = '';
                $nombreCoordinador = '';
                $condonacionInicial = '';
                $valueCondonacionInicial = '';
                $endCondonation = '';
                $valueEndCondonation = '';
                $paymentAgreed = '';
                $creditPaymentDay = '';
                $documentsRequired = '';
                //APP
                $customerTelephone = '';            

                if(!empty($log->adjusted_obligations)){
                    /** @var  $obligation AdjustedObligation*/
                    foreach ($log->adjusted_obligations as $obligation){
                        if($obligation->log_id == $history->log_id){
                            $fechaDocumentacion = (!empty($obligation->documentation_date))?$obligation->documentation_date->format('d-m-Y'):"";
                            $nombreOficina = utf8_decode($obligation->office_name);
                            $nombreCoordinador = utf8_decode($obligation->coordinator_name);
                            $condonacionInicial = $obligation->initial_condonation;
                            $valueCondonacionInicial = $obligation->value_initial_condonation;
                            $endCondonation = $obligation->end_condonation;
                            $valueEndCondonation = $obligation->value_end_condonation;
                            $paymentAgreed = $obligation->payment_agreed;
                            $creditPaymentDay = $obligation->credit_payment_day;
                            $documentsRequired = $obligation->documents_required;
                            $customerTelephone = $obligation->customer_telephone;
                        }
                    }
                }

                if(empty($paymentAgreed)){
                    $paymentAgreed = $history->payment_agreed;
                }

                $saldoNor = 0;
                $codigo = null;
                $necesitaPagareUnificacion = 1;
                $necesitaPagareACPK = 1;
                foreach ($history->history_details as $detail) {
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
                
                foreach ($history->history_details as $detail){

                    /*CAMPOS NUEVOS*/
                    $consecutivo_obligacion = $detail->sequential_obligation;
                    /*CAMPOS NUEVOS*/

                    $razonrechazo = str_replace(',',' ',$history->reason_rejection);
                    $razonrechazo = Log::parraf2Line($razonrechazo);
                    $ofertaVehiculo = null;
                    if (!empty($history->history_payment_vehicles)){
                        /** @var  $ofertaVehiculo \App\Model\Entity\HistoryPaymentVehicle */
                        $ofertaVehiculo = $history->history_payment_vehicles[0];
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
                        /** @var HistoryNormalization $normalizacion */
                        if(!empty($history->history_normalizations)) {
                            foreach ($history->history_normalizations as $normalizacion) {
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
                         /** @var HistoryNormalization $normalizacion */
                         if(!empty($history->history_punished_portfolios)) {
                             foreach ($history->history_punished_portfolios as $normalizacionP) {
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

                    $fields = [
                        'fecha' => $history->created->format('d-m-Y H:i:s'),
                        'type_identification' => utf8_decode($log->customer_type_identification->type),
                        'codigo' => $log->customer_type_identification->id,
                        'identification' => $log->customer_identification,
                        'estado' => $history->history_status->name,
                        'obligation' => $detail->obligation,
                        'customer_revenue' => $history->income,
                        'customer_paid_capacity' => $history->payment_capacity,
                        'initial_payment_punished' => $history->initial_payment_punished,
                        'customer_email' => $history->customer_email,
                        'total_debt' => $detail->total_debt,
                        'previous_minimum_payment' => $detail->minimum_payment,
                        'initial_fee' => $cuota,
                        'strategy' => utf8_decode($detail->strategy),
                        'code_strategy' => (!is_null($codigo) && $detail->type_strategy == 1)?$codigo:Obligation::getCogigo($detail->type_strategy),
                        'Cuota_Proyctada' => $nuevaCuota,
                        'months_term' => $tiempo,
                        'annual_effective_rate' => $tasaAnual,
                        'nominal_rate' => $tasaMensual,
                        'user_dataweb' => utf8_decode($log->user->name),
                        'payment_agreed' => $paymentAgreed,
                        'documentation_date' => $fechaDocumentacion,
                        'office_name' => $nombreOficina,
                        'documentation_date_2' => $fechaDocumentacion,
                        'empresa' => (!empty($log->user->busines)) ? h($log->user->busines->name) : '',
                        'alternativa' => $history->alternative,
                        'Aprobado por comite' => ($history->history_status_id == HistoryStatusesTable::ACEPTADA_COMITE)?'Si':'',
                        'Coordinador' => $nombreCoordinador,
                        'reason_rejection' => $razonrechazo,
                        'initial_condonation' => $condonacionInicial,
                        'value_initial_condonation' => $valueCondonacionInicial,
                        'end_condonation' => $endCondonation,
                        'value_end_condonation' => $valueEndCondonation,
                        'pago_total_vehiculo'=> '',
                        'pago_total_vehiculo_experto'=> '',
                        'oferta_cliente_vehiculo'=> '',
                        'date_valorization'=> '',
                        'type_valorization'=> '',
                        'value_valorization'=> '',
                        'value_parking'=> '',
                        'value_subpoena'=> '',
                        'value_taxes'=> '',
                        'value_others'=> '',
                        'cnd'=>  $history->cnd,
                        'documentos'=>  ($history->documents_delivered)?'Si':'',
                        'user_documents_delivered' => $history->user_documents_delivered,
                        'documentos_fecha'=>  (!empty($history->date_documents_delivered))?$history->date_documents_delivered->format('d-m-Y H:i:s'):'',
                        'desiste'=>  ($history->customer_desist)?'Si':'',
                        'user_customer_desist' => $history->user_customer_desist,
                        'desiste_fecha'=>  (!empty($history->date_customer_desist))?$history->date_customer_desist->format('d-m-Y H:i:s'):'',
                        'credit_payment_day' => '',
                        'documents_required' => '',
                        'is_uvr' => $detail->currency == 'UVR' ? "1" : " ",
                        'campania' => $history->customer_observations,
                        'observaciones' => $history->customer_alternatives,
                        'Fecha Acceso App' =>  (!empty($history->app_access_date))?$history->app_access_date->format('d-m-Y H:i:s'):'',
                        'Motivo de No Pago' => (!empty($history->negotiation_reason->Descption_reason)) ? h($history->negotiation_reason->Descption_reason) : '',
                        'Celular' => $customerTelephone,
                        'Aprobación TyC' => $history->approval_tyc,
                        'ID Paquete documental' => '',
                        'Fecha Agendamiento' => (!empty($history->scheduling_date))?$history->scheduling_date->format('d-m-Y H:i:s'):'',
                        'Motivo Agendamiento' => $history->scheduling_reason,
                        'IP' => $history->ip_address,
                        'Id session canal' => $history->session_id,
                        'sequential_customer' => $consecutivo_cliente, /*CAMPOS NUEVOS*/
                        'sequential_obligation' => $consecutivo_obligacion /*CAMPOS NUEVOS*/
                    ];

                    if($detail->type_strategy == 3){
                        if($detail->retrenched_policy == 3){
                            $fields['code_strategy'] = Obligation::getCogigo(3, 0);
                        }else {
                            $fields['code_strategy'] = Obligation::getCogigo(3, 1);
                        }  
                    }

                    if ($detail->type_strategy == 1) {
                        $fields['code_strategy'] = Obligation::getCogigo($detail->type_strategy, $necesitaPagareUnificacion);
                    }

                    if ($detail->type_strategy == 12) {
                        $fields['code_strategy'] = Obligation::getCogigo($detail->type_strategy, $necesitaPagareACPK);
                    }
                    if ($detail->type_strategy == 1 || $detail->type_strategy == 12) {
                        if ($documentsRequired !== '' && $documentsRequired !== null) {
                            $fields['documents_required'] = (string) sprintf("%01d", $documentsRequired);
                        }
                        $fields['credit_payment_day'] = $creditPaymentDay ? (string) sprintf("%02d", $creditPaymentDay) : '';
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

                        $fields['pago_total_vehiculo'] = $ofertaVehiculo->total_payment;
                        $fields['pago_total_vehiculo_experto'] = $ofertaVehiculo->total_payment_expert;
                        $fields['oferta_cliente_vehiculo'] = $ofertaVehiculo->customer_offer;

                        $fields['date_valorization'] = (!empty($ofertaVehiculo->date_valorization))?$ofertaVehiculo->date_valorization->format('d-m-Y'):"";
                        $fields['type_valorization'] = $typeAvaluo;
                        $fields['value_valorization'] = $ofertaVehiculo->value_valorization;
                        $fields['value_parking'] = $ofertaVehiculo->value_parking;
                        $fields['value_subpoena'] = $ofertaVehiculo->value_subpoena;
                        $fields['value_taxes'] = $ofertaVehiculo->value_taxes;
                        $fields['value_others'] = $ofertaVehiculo->value_others;
                    }
                    fputcsv($fp, $fields);
                }
            }

        }
        fclose($fp);
       return $nameFile;

    }

    static function parraf2Line($string)
    {    $line="";
        $trozo = explode("\n",$string);
        for($i=0;$i<count($trozo);$i++)
        {    if(!empty($trozo[$i]))
            $line=$line.trim($trozo[$i]).' ';
        }
        $line = trim($line);
        return $line;
    }
}
