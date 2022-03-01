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
 * LogRediferido Entity
 *
 * @property int $id
 * @property int $log_id
 * @property \Cake\I18n\FrozenTime $creado
 * @property \Cake\I18n\FrozenTime $modificado
 * @property string $type_identification
 * @property int $identification
 * @property string $obligation
 * @property string $origen
 * @property \Cake\I18n\FrozenTime $fecha_compromiso
 * @property float $pago_real
 * @property int $plazo
 * @property string $codigo_estrategia
 * @property string $tipo_rediferido
 * @property bool $cliente_desiste
 * @property string $reversan
 * @property int $sequential_obligation
 *
 * @property \App\Model\Entity\Log $log
 */
class LogRediferido extends Entity
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

    public static function PersistenceLogsRediferido($historyCustomer){
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
            $logRediferidoTable = TableRegistry::get('log_rediferido');
            $logRediferido = $logRediferidoTable->newEntity();
            
            $arrayLogs = [];
            foreach ($adjusted_obligations_details as $detail) {
                if ($detail->type_strategy == 3){
                    if($detail->retrenched_policy == 3){
                        $negociacion = Obligation::getCogigo($detail->type_strategy,0);
                    } else {
                        $negociacion = Obligation::getCogigo($detail->type_strategy);
                    }
                    $logRediferido->log_id = $adjusted_obligations->log_id;
                    $logRediferido->type_identification = $adjusted_obligations->type_identification;
                    $logRediferido->identification = $adjusted_obligations->identification;
                    $logRediferido->obligation = $detail->obligation;
                    $logRediferido->origen = $detail->origin;
                    $logRediferido->fecha_compromiso = $adjusted_obligations->documentation_date;
                    $logRediferido->pago_real = ($detail->payment_agreed)?$detail->payment_agreed:0;
                    $logRediferido->plazo = $detail->months_term;
                    $logRediferido->codigo_estrategia = null;
                    $logRediferido->tipo_rediferido = $negociacion;
                    $logRediferido->cliente_desiste = ($history->customer_desist)?1:0;
                    $logRediferido->reversan = null;
                    $logRediferido->sequential_obligation = $detail->sequential_obligation;
    
                    $timeInicio = microtime(true);
                    array_push($arrayLogs, $logRediferido);
                    $logRediferido = $logRediferidoTable->newEntity();
                }
            }
            try {
                foreach($arrayLogs as $logRediferido){
                    $result = $logRediferidoTable->save($logRediferido);
                    if ($result == null || $result == 0 || $result < 0) {
                        throw new Exception(
                            "Se genero error al momento de guardar la informacion a la tabla log rediferido."
                        );
                    }
                }
                $timeFinal = microtime(true);
                $duracion = ($timeFinal) - ($timeInicio);
                $logTran = LogTransaccionController::EscribirTiempo('Finalizar','Insertar logRediferido','logRediferido','PersistencelogRediferido',$idLogL,$duracion);
                
            } catch (Exception $th) {
                throw new Exception ("Error en el metdo de guardar el log rediferido",0,$th);
            }

        }catch (Exception $th) {
            throw new Exception ("Error en el metdo de generar el log rediferido",0,$th);
        }
    }
    public static function ClienteDesisteRediferido($arrayDesisteRediferido){
        try {
            $logRediferidoTable = TableRegistry::get('log_rediferido');
            $historyCustomerTable = TableRegistry::get('history_customers');
            $historyCustomer = $historyCustomerTable->get($arrayDesisteRediferido['id']);
            $timeInicioClienteDesisConteo = microtime(true);
            $logRediferidoTableGetCount = $logRediferidoTable->find()->where(['log_id' => $historyCustomer->log_id])->count();
            $timeFinalClienteDesisConteo = microtime(true);
            $duracion = ($timeFinalClienteDesisConteo) - ($timeInicioClienteDesisConteo);
            $logRedi = LogTransaccionController::EscribirTiempo('Conteo','Actualización logRediferido','logRediferido','ClienteDesiste',$historyCustomer->log_id,$duracion);
            if ($logRediferidoTableGetCount > 0) {
                $timeInicioClienteDesisUpdate = microtime(true);
                $result = $logRediferidoTable->updateAll(
                    [
                        'cliente_desiste' => $arrayDesisteRediferido['cliente_desiste'],
                        'modificado' => $arrayDesisteRediferido['modificado'],
                    ],
                    ['log_id' => $historyCustomer->log_id]
                );
                $timeFinalClienteDesisUpdate = microtime(true);
                $duracion = ($timeFinalClienteDesisUpdate) - ($timeInicioClienteDesisUpdate);
                $logRedi = LogTransaccionController::EscribirTiempo('Actualizacion','Actualización logRediferido','logRediferido','ClienteDesiste',$historyCustomer->log_id,$duracion);
                if ($result == null){
                    throw new Exception("Se presento un error al momento de actualizar el log de rediferido en función cliente desiste");
                }
            }

            
            $mensaje = "Finaliza la función de cliente desiste de negociación";
            $parametros = [
                'idHistory' => $historyCustomer->id,
                'idLog' => $historyCustomer->log_id,
                'customer' => $historyCustomer->customer_id,
                'capacidad_pago' => $historyCustomer->payment_capacity
            ];
            $logRedi = LogTransaccionController::EscribirLog($mensaje,$parametros);
        } catch (Exception $th) {
            throw new Exception("Se presento un error en la función de cliente desiste de negociación", 0, $th);
        }
    }
}
