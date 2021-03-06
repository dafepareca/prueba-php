<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Controller\LogTransaccionController;
use App\Model\Entity\Charge;
use App\Model\Entity\Customer;
use App\Model\Entity\Obligation;
use App\Model\Table\AccessTypesActivitiesTable;
use App\Model\Table\ChargesTable;
use App\Model\Table\HistoryStatusesTable;
use App\Model\Table\TypeObligationsTable;
use Cake\Cache\Cache;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerTypeIdentificationsTable $CustomerTypeIdentifications
 * @property \App\Model\Table\WorkActivitysTable $WorkActivitys
 * @property \App\Model\Table\CertificatesTable $Certificates
 * @property \App\Model\Table\NormalizationReasonsTable $NormalizationReasons
 * @property \App\Model\Table\QueriesCustomersTable $QueriesCustomers
 * @property \App\Model\Table\HistoryCustomersTable $HistoryCustomers
 * @property \App\Model\Table\ObligationsTable $Obligations
 * @property \App\Model\Table\LegalCodesTable $LegalCodes
 */
class BaseCustomersController extends AppController
{

    var $castigadaConsumo = false;
    var $castigadaHp = false;
    var $castigadaVh = false;

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }


    /**
     * Index method
     * @return \Cake\Network\Response|null
     */
    public function consult()
    {
        $connection = ConnectionManager::get('default');
        $connection->begin();

        try {

            $timeInicio = microtime(true);
            $this->loadModel('CustomerTypeIdentifications');
            $this->loadModel('Customers');
    
            $customer = $this->Customers->newEntity();
            $totales = $this->totales([]);
    
            $obligations = [];
    
            $typeIdentifications = $this->CustomerTypeIdentifications->find('list')->toArray();
            Cache::delete($this->Auth->user('session_id').'customer');
            Cache::delete($this->Auth->user('session_id').'obligaciones');
            Cache::delete($this->Auth->user('session_id').'capacidad_pago');
            Cache::delete($this->Auth->user('session_id').'negociacion');
            Cache::delete($this->Auth->user('session_id').'normalizacion');
            Cache::delete($this->Auth->user('session_id').'log');
            Cache::delete($this->Auth->user('session_id').'propuesta_castigada');
            Cache::delete($this->Auth->user('session_id').'vehiculo');
    
            if($this->request->is('post')){
                $customer = $this->Customers->find()
                    ->contain(
                        [
                            'Charges' => [
                                'fields' => ['Charges.id','Charges.state']
                            ],
                            'CustomerTypeIdentifications',
                            #'HistoryCustomers',
                            /*'QueriesCustomers' => [
                                'queryBuilder' =>  function ($q) {
                                    return $q->where(function ($exp, $q) {
                                        return $exp->between('created',date('Y-m-01 00:00:00'),date('Y-m-30 11:59:59'));
                                    });
                                },
                            ]*/
                        ])
                    ->where([
                        'customer_type_identification_id' => $this->request->getData('customer_type_identification_id'),
                        'Customers.id' => $this->request->getData('identification'),
                        'Charges.state' => ChargesTable::ACTIVO
                    ])
                    ->first();
    
                if($customer){
    
                    if(!empty($customer->cnd)){
                        $cndCodes = TableRegistry::get('CndCodes');
                        /** @var  $code CndCode*/
                        $code = $cndCodes->find()->where(['code'=>$customer->cnd])->first();
    
                        if($code){
                            if($code->not_negotiate){
                                $customer->cndNegociar = false;
                            }
                            $customer->cndMensaje = $code->message;
                        }
                    }
    
                    $this->loadModel('AdjustedObligations');
                    $this->loadModel('HistoryCustomers');
                    $adjustedObligations = $this->AdjustedObligations->find()
                        ->select(['id','identification','pending_committee','log_id'])
                        ->where(
                            [
                                'identification' => $customer->id
                            ]
                        )->order(['id' => 'DESC'])
                    ->first();
    
                    if($adjustedObligations){
                        if($adjustedObligations->pending_committee){
                            $state = HistoryStatusesTable::COMITE;
                        }else{
                            $historys = $this->HistoryCustomers->find()
                                    ->select(['id','log_id','history_status_id'])
                                    ->where(['log_id' => $adjustedObligations->log_id])
                                    ->first();
                            $state = $historys->history_status_id;
                            if (!$historys){
                                $historys = $this->HistoryCustomers->find()
                                    ->select(['id','log_id','history_status_id'])
                                    ->order(['id' => 'DESC'])
                                    ->first();
                                $state = $historys->history_status_id;
                            }
                        }
                    }else{
                        $historys = $this->HistoryCustomers->find()
                                ->select(['id','log_id','history_status_id'])
                                ->order(['id' => 'DESC'])
                                ->first();
                        $state = $historys->history_status_id;
                    }
    
                    Cache::write($this->Auth->user('session_id').'customer',$customer);
    
                    $this->loadModel('WorkActivitys');
                    $this->loadModel('NormalizationReasons');
                    $this->loadModel('Certificates');
                    $this->loadModel('Obligations');
    
                    /** @var  $customer Customer*/
                    $customer = Cache::read($this->Auth->user('session_id').'customer');
                    $obligations = $this->Obligations->find()
                        ->contain([
                            'TypeObligations' => ['fields' => ['TypeObligations.id','TypeObligations.type','TypeObligations.term']],
                            'Charges' => ['fields' => ['Charges.id','Charges.state']]
                        ])
                        ->where(['type_obligation_id <>' => 0, 'customer_id' => $customer->id, 'customer_type_identification_id' => $customer->customer_type_identification_id,'Charges.state' => ChargesTable::ACTIVO])                    
                        ->order(['total_debt'=>'desc'])
                        ->all();
    
                    Cache::write($this->Auth->user('session_id').'obligaciones',$obligations);
    
                    $totales = $this->totales($obligations);
    
                    $this->Log(
                        [
                            'customer_type_identification_id' => $customer->customer_type_identification_id,
                            'customer_identification' => $customer->id,
                            'answer' => 1
                        ]
                    );
    
    
                    /** @var  $customer Customer*/
                    $customer = Cache::read($this->Auth->user('session_id').'customer');
    
                    $this->set(compact('state'));
                    $this->set(compact('customer'));
                    $this->set('_serialize', ['customer']);
                    $this->set(compact('typeIdentifications'));
                    

                    $informacion = $this->createHistory($obligations,$customer);

                    if(count($obligations)>0){
                        $this->Flash->success(__('Informaci??n cliente'));
                    }else{
                        $this->Flash->success(__('El cliente no cuenta con obligaciones para negociar.'));
                    }
    
                }else{
                    $this->Log(
                        [
                            'customer_type_identification_id' => $this->request->getData('customer_type_identification_id'),
                            'customer_identification' => $this->request->getData('identification'),
                            'answer' => 0
                        ]
                    );
                    $this->Flash->error(__('El cliente no se encuentra en la base.'));
                }
            }
    
            $this->set(compact('obligations'));
            $this->set(compact('totales'));
            $this->set(compact('typeIdentifications'));
            $this->set('castigadaConsumo',$this->castigadaConsumo);
            $this->set('castigadaHp',$this->castigadaHp);
            $this->set('castigadaVh',$this->castigadaVh);
    
            $this->viewBuilder()->setTemplatePath('/Customers');
            $mensaje = "Finalizo la funci??n consulta";
            $idLogL = Cache::read($this->Auth->user('session_id') . 'log');
            $customerL = Cache::read($this->Auth->user('session_id').'customer');
            $parametros = [
                'idHistory' => $informacion,
                'idLog' => $idLogL,
                'customer' => $customerL->id,
                'cargue' => $customerL->charge_id,
                'capacidad_pago' => $customerL->payment_capacity
            ];
            $logTran = LogTransaccionController::EscribirLog($mensaje,$parametros);
    
            if($this->request->is('post')) {
                $timeFinal = microtime(true);
                $duracion = ($timeFinal) - ($timeInicio);
                $logTran = LogTransaccionController::EscribirTiempo('Consulta','Funcion completa','BaseCustomersController','consult',$idLogL,$duracion);
            }
                
            $connection->commit();

            $this->render('consult');
        } catch (Exception $th) {
            $mensaje = "Se presento un error en la funcion estado consulta";
            $parametro = [
                'customer' => $customer->id,
                'cargue' => $customer->charge_id
            ];
            $logTran = LogTransaccionController::EscribirLog($mensaje,$parametro,$th);
            
            $connection->rollback();
        }
        
    }


    /**
     * @param $obligaciones
     * @return array
     */
    private function totales($obligaciones){

        $totales = [
            'hipotecario' => [
                'total' => 0,
                'minimos' => 0,
                'cuotas' => 0,
            ],
            'vehiculo' => [
                'total' => 0,
                'minimos' => 0,
                'cuotas' => 0,
            ],
            'rotativos' => [
                'total' => 0,
                'minimos' => 0,
                'cuotas' => 0,
            ],
            'fijos' => [
                'total' => 0,
                'minimos' => 0,
                'cuotas' => 0,
            ],
            'total' => [
                'total' => 0,
                'minimos' => 0,
                'cuotas' => 0,
            ],
            'total_castigada' => [
                'total' => 0,
                'minimos' => 0,
                'cuotas' => 0,
            ],
            'total_vigente' => [
                'total' => 0,
                'minimos' => 0,
                'cuotas' => 0,
            ]
        ];

        /** @var  $obligacion Obligation*/
        foreach($obligaciones as $obligacion){
            
            $tipo = $obligacion->type_obligation_id;

            $this->legalProcess($obligacion);

            if($obligacion->esConsumo() && $obligacion->punished){
                $this->castigadaConsumo = true;
            }

            if($tipo == TypeObligationsTable::HIP && $obligacion->punished){
                $this->castigadaHp = true;
            }

            if($tipo == TypeObligationsTable::VEH && $obligacion->punished){
                $this->castigadaVh = true;
            }

            if($tipo == TypeObligationsTable::HIP){
                $totales['hipotecario']['total'] += $obligacion->total_debt;
                $totales['hipotecario']['minimos'] += $obligacion->minimum_payment;
                $totales['hipotecario']['cuotas'] += $obligacion->fee;
            }elseif($tipo == TypeObligationsTable::VEH){
                $totales['vehiculo']['total'] += $obligacion->total_debt;
                $totales['vehiculo']['minimos'] += $obligacion->minimum_payment;
                $totales['vehiculo']['cuotas'] += $obligacion->fee;
            }elseif($tipo == TypeObligationsTable::CXF){
                $totales['fijos']['total'] += $obligacion->total_debt;
                $totales['fijos']['minimos'] += $obligacion->minimum_payment;
                $totales['fijos']['cuotas'] += $obligacion->fee;
            }elseif($tipo == TypeObligationsTable::TDC || TypeObligationsTable::CXR || TypeObligationsTable::SOB){
                $totales['rotativos']['total'] += $obligacion->total_debt;
                $totales['rotativos']['minimos'] += $obligacion->minimum_payment;
                $totales['rotativos']['cuotas'] += $obligacion->fee;
            }

            if($obligacion->punished){
                $totales['total_castigada']['total'] += $obligacion->total_debt;
                $totales['total_castigada']['minimos'] += $obligacion->minimum_payment;
                $totales['total_castigada']['cuotas'] += $obligacion->fee;
            }else{
                $totales['total_vigente']['total'] += $obligacion->total_debt;
                $totales['total_vigente']['minimos'] += $obligacion->minimum_payment;
                $totales['total_vigente']['cuotas'] += $obligacion->fee;
            }

            $totales['total']['total'] += $obligacion->total_debt;
            $totales['total']['minimos'] += $obligacion->minimum_payment;
            $totales['total']['cuotas'] += $obligacion->fee;

        }

        return $totales;

    }

    public function legalProcess($obligacion){
        /** @var  $obligacion Obligation*/
        $estapas = [
            'AVAL', 'CAPT', 'DEMA',
            'EMBA', 'LIQU', 'MAND',
            'NOTI', 'OCAP', 'REMA',
            'REPA', 'SECU', 'SENT',
        ];

        $this->loadModel('LegalCodes');

        /** @var  $customer Customer*/
        $customer = Cache::read($this->Auth->user('session_id').'customer');
        $code = $this->LegalCodes->find()->where(['code'=>$obligacion->step])->count();

        if(!$customer->legalProcess && $code > 0){
            $customer->legalProcess = true;
            Cache::write($this->Auth->user('session_id').'customer',$customer);
        }

    }

    public function information()
    {

        $this->autoRender = false;

        if (($posts = Cache::read($this->Auth->user('session_id') . 'customer')) === false) {
            $this->Flash->error(__('Debe consultar el cliente...'));
            return $this->redirect(['controller' => 'customers', 'action' => 'consult']);
        }

        $this->loadModel('WorkActivitys');
        $this->loadModel('NormalizationReasons');
        $this->loadModel('Certificates');
        $this->loadModel('Obligations');


        /** @var  $customer Customer */
        $customer = Cache::read($this->Auth->user('session_id') . 'customer');
        $obligations = $this->Obligations->find()
            ->where(['restructuring' => 0, 'customer_id' => $customer->id])
            ->contain([
                'TypeObligations'
            ])
            ->all();

        $noObligations = $this->Obligations->find()
            ->where(['restructuring' => 1, 'customer_id' => $customer->id])
            ->contain([
                'TypeObligations'
            ])
            ->all();

        $this->set(compact('obligations'));
        $this->set(compact('noObligations'));

        $workActivitys = $this->WorkActivitys->find('list')->toArray();
        $normalizationReasons = $this->NormalizationReasons->find('list')->toArray();
        $certificates = $this->Certificates->find('list')->toArray();

        $this->set(compact('workActivitys'));
        $this->set(compact('normalizationReasons'));
        $this->set(compact('certificates'));

        $this->viewBuilder()->setTemplatePath('/Customers');
        $this->render('consult');
    }

    public function update($id)
    {
        $this->autoRender = false;
        $this->response->type('json');


        if ($this->request->is(['patch', 'post', 'put'])) {
            $customer = $this->Customers->get($id);

            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
            if ($this->Customers->save($customer)) {
                $result = [
                    'success' => true,
                    'data' => [],
                    'message' => __('Informaci??n actualizada')
                ];

                $customer = Cache::read($this->Auth->user('session_id') . 'customer');
                $customer->name = $this->request->getData('name');
                $customer->email = $this->request->getData('email');
                Cache::write($this->Auth->user('session_id') . 'customer', $customer);

            } else {
                $result = [
                    'success' => false,
                    'data' => [],
                    'message' => __('Error actualizando la informaci??n')
                ];
            }
        }
        echo json_encode($result);
    }

}
