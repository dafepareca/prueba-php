<?php
namespace App\Controller\Asesor;

use App\Controller\AppController;
use App\Model\Entity\LogTransactional;
use App\Model\Entity\LogRediferido;
use App\Model\Table\HistoryStatusesTable;
use Cake\Database\Exception;
use App\Controller\LogTransaccionController;
use Cake\Datasource\ConnectionManager;

/**
 * HistoryCustomers Controller
 *
 * @property \App\Model\Table\HistoryCustomersTable $HistoryCustomers
 *
 * @method \App\Model\Entity\HistoryCustomer[] paginate($object = null, array $settings = [])
 */
class HistoryCustomersController extends AppController
{

    /**
    * Index method
    * @return \Cake\Network\Response|null
    */
    public function index()
    {
        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['Customers', 'HistoryStatuses'],
            'order' => ['HistoryCustomers.id' => 'ASC']
        ];
        $historyCustomers = $this->paginate($this->HistoryCustomers);
        $this->set(compact('historyCustomers'));
        $this->set('_serialize', ['historyCustomers']);

        $this->viewBuilder()->setTemplatePath('/HistoryCustomers');
    }

    /**
     * View method
     *
     * @param string|null $id History Customer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $historyCustomer = $this->HistoryCustomers->get($id, [
            'contain' => ['HistoryStatuses', 'HistoryDetails'=>['TypeObligations'], 'HistoryNormalizations','HistoryPunishedPortfolios']
        ]);

        $this->set('historyCustomer', $historyCustomer);
        $this->set('_serialize', ['historyCustomer']);

        $this->viewBuilder()->setTemplatePath('/HistoryCustomers');
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $historyCustomer = $this->HistoryCustomers->newEntity();
        if ($this->request->is('post')) {
            $historyCustomer = $this->HistoryCustomers->patchEntity($historyCustomer, $this->request->getData());
            if ($this->HistoryCustomers->save($historyCustomer)) {
                $this->Flash->success(__('The {0} has been saved.', 'History Customer'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'History Customer'));
            }
        }
        $customers = $this->HistoryCustomers->Customers->find('list', ['limit' => 200]);
        $historyStatuses = $this->HistoryCustomers->HistoryStatuses->find('list', ['limit' => 200]);
        $this->set(compact('historyCustomer', 'customers', 'historyStatuses'));
        $this->set('_serialize', ['historyCustomer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id History Customer id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $historyCustomer = $this->HistoryCustomers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $historyCustomer = $this->HistoryCustomers->patchEntity($historyCustomer, $this->request->getData());
            if ($this->HistoryCustomers->save($historyCustomer)) {
                $this->Flash->success(__('The {0} has been saved.', 'History Customer'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'History Customer'));
            }
        }
        $customers = $this->HistoryCustomers->Customers->find('list', ['limit' => 200]);
        $historyStatuses = $this->HistoryCustomers->HistoryStatuses->find('list', ['limit' => 200]);
        $this->set(compact('historyCustomer', 'customers', 'historyStatuses'));
        $this->set('_serialize', ['historyCustomer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id History Customer id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $historyCustomer = $this->HistoryCustomers->get($id);
        if ($this->HistoryCustomers->delete($historyCustomer)) {
            $this->Flash->success(__('The {0} has been deleted.', 'History Customer'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'History Customer'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function history($id){
        $historyCustomer = $this->HistoryCustomers->find()
            ->contain(['HistoryStatuses','Users'])
            ->where(['HistoryCustomers.customer_id'=>$id])
            ->order('HistoryCustomers.created DESC')
            ->all();

        $this->set('historyCustomer', $historyCustomer);

        $this->viewBuilder()->setTemplatePath('/HistoryCustomers');
    }

    public function entregaDocumentos(){
        $connection = ConnectionManager::get('default');
        $connection->begin();
        $this->autoRender = false;
        $this->response->type('json');

        $resp = [
            'success' => true,
            'data' => [],
            'message' => __('Cliente firmó documentos con exito.'),
        ];

        try {
            $HistoryCustomersGetCount = $this->HistoryCustomers->find()->where(['id' => $this->request->getQuery('id')])->count();

            if($HistoryCustomersGetCount > 0) {
                $HistoryCustomersUpdate = $this->HistoryCustomers->updateAll(
                    [
                        'documents_delivered' => $this->request->getQuery('valor'),
                        'date_documents_delivered' => date('Y-m-d H:i:s'),
                        'user_documents_delivered' => $this->Auth->user('name'),
                    ],
                    [
                        'id' => $this->request->getQuery('id')
                    ]
                );
                if ($HistoryCustomersUpdate == null || $HistoryCustomersUpdate == 0 || $HistoryCustomersUpdate < 0) {
                    throw new Exception("Se genero un error al momento de realizar actualización de la información en la tabla HistoryCustomers en la funcion entregaDocumentos del controlador HistoryCustomers (ASESOR)");
                }
            }
    
            //Actualizar en logTransactional
            $arrayDocumentos = array(
                'documents_delivered' => $this->request->getQuery('valor'),
                'date_documents_delivered' => date('Y-m-d H:i:s'),
                'user_documents_delivered' => $this->Auth->user('name'),
                'id' => $this->request->getQuery('id')
            );
            $logTransactional = LogTransactional::ClienteEntregaDocumentos($arrayDocumentos);

            $connection->commit();
        } catch (Exception $th) {
            $resp["success"] = false;
            $resp['message'] = __('Error inesperado, por favor intente de nuevo.');

            $mensaje = "Se presento un error en la funcion entrega de documentos en perfil asesor";
            $parametro = [
                'history_id' => $this->request->getQuery('id')
            ];
            $logTran = LogTransaccionController::EscribirLog($mensaje,$parametro,$th);
            $connection->rollback();
        }

        echo json_encode($resp);
    }

    public function desisteNegociacion(){
        $connection = ConnectionManager::get('default');
        $connection->begin();
        $this->autoRender = false;
        $this->response->type('json');

        $resp = [
            'success' => true,
            'data' => [],
            'message' => __('Negociación desistida con exito.'),
        ];

        try{
            
             $HistoryCustomersGetCount = $this->HistoryCustomers->find()->where(['id' => $this->request->getQuery('id')])->count();

            if($HistoryCustomersGetCount > 0) {
                $HistoryCustomersUpdate = $this->HistoryCustomers->updateAll(
                    [
                        'history_status_id' => HistoryStatusesTable::PENDIENTE,
                        'customer_desist' => $this->request->getQuery('valor'),
                        'date_customer_desist' => date('Y-m-d H:i:s'),
                        'user_customer_desist' => $this->Auth->user('name'),
                        'reason_rejection' => 'Cliente desiste de negociacion',
                    ],
                    [
                        'id' => $this->request->getQuery('id'),
                    ]
                );
                if ($HistoryCustomersUpdate == null || $HistoryCustomersUpdate == 0 || $HistoryCustomersUpdate < 0) {
                    throw new Exception("Se genero un error al momento de realizar actualización de la información en la tabla HistoryCustomers en la funcion desisteNegociacion del controlador HistoryCustomers (ASESOR)");
                }
            }
            
            //Actualizar en logTransactional
            $arrayDesiste = array(
                'estado' => 'Declinada',
                'customer_desist' => $this->request->getQuery('valor'),
                'date_customer_desist' => date('Y-m-d H:i:s'),
                'user_customer_desist' => $this->Auth->user('name'),
                'id' => $this->request->getQuery('id'),
                'reason_rejection' => 'Cliente desiste de negociacion',
            );
            $arrayDesisteRediferido = array(
                'cliente_desiste' => $this->request->getQuery('valor'),
                'modificado' => date('Y-m-d H:i:s'),
                'id' => $this->request->getQuery('id'),
            );
            $logRediferido = LogRediferido::ClienteDesisteRediferido($arrayDesisteRediferido);
            $logTransactional = LogTransactional::ClienteDesiste($arrayDesiste);
            $connection->commit();

        }catch (Exception $th) {
            $resp["success"] = false;
            $resp['message'] = __('Error inesperado, por favor intente de nuevo.');

            $mensaje = "Se presento un error en la funcion desiste de negociación perfil asesor";
            $parametro = [
                'history_id' => $this->request->getQuery('id')
            ];
            $logTran = LogTransaccionController::EscribirLog($mensaje,$parametro,$th);
            $connection->rollback();
        }

        echo json_encode($resp);

    }
}
