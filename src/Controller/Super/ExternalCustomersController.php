<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use App\Model\Table\RolesTable;
use App\Model\Table\UserStatusesTable;
use Cake\Event\Event;

/**
 * ExternalCustomers Controller
 *
 * @property \App\Model\Table\ExternalCustomersTable $ExternalCustomers
 */
class ExternalCustomersController extends AppController{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->setConfig('unlockedActions', ['add', 'edit']);
    }

    /**
     * View method
     *
     * @param string|null $id ExternalCustomer id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $externalCustomer = $this->ExternalCustomers->get($id, [
            'contain' => ['AccessGroups', 'Customers', 'Roles', 'Attachments', 'UserStatuses']
        ]);
        $this->set('externalCustomer', $externalCustomer);
        $this->set('_serialize', ['externalCustomer']);
    }

    /**
     * select_customer method
     * @return \Cake\Network\Response|null
     */

    public function select_customer()
    {
        if ($this->request->is('post')) {
            return $this->redirect(['action' => 'add', 'customer_id' => $this->request->getData('customer_id')]);
        }
        $externalCustomer = $this->ExternalCustomers->newEntity();
        $customers = $this->ExternalCustomers->Customers->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('externalCustomer','customers'));
        $this->set('_serialize', ['externalCustomer']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customer_id = $this->request->getQuery('customer_id');
        if(!is_null($customer_id) && $customer_id > 0){
            $customer = $this->ExternalCustomers->Customers->get($customer_id, [
                'fields'    => ['Customers.id', 'Customers.name'],
                'contain'   => [
                    'BusinessUnits' => [
                        'Campaigns' => [
                            'conditions' => [
                                'Campaigns.customer_id' => $customer_id,
                                'Campaigns.status' => 1
                            ],
                            'sort' => [ 'Campaigns.name ASC' ]
                        ],
                        'sort' => [ 'BusinessUnits.name ASC' ]
                    ]
                ]
            ]);
        }else{
            $this->Flash->error(__('No customer selected'));
            return $this->redirect(['controller' => 'Users', 'action' => 'index']);
        }
        $externalCustomer = $this->ExternalCustomers->newEntity();
        if ($this->request->is('post')) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            $externalCustomer = $this->ExternalCustomers->patchEntity($externalCustomer, $this->request->getData());
            $externalCustomer->role_id = RolesTable::External;
            $externalCustomer->user_status_id = UserStatusesTable::Active;
            $externalCustomer->customer_id = $customer_id;
            unset($this->request->data['campaigns']);
            if ($result = $this->ExternalCustomers->save($externalCustomer)) {
                $this->Flash->success(__('The {0} has been saved.', 'External Customer'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'External Customer'));
            }
        }
        $accessGroups = $this->ExternalCustomers->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('externalCustomer','accessGroups', 'customer'));
        $this->set('_serialize', ['externalCustomer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id ExternalCustomer id.
     * @return \Cake\Http\Response
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $externalCustomer = $this->ExternalCustomers->get($id, [
            'contain' => [
                'Campaigns' => [
                    'BusinessUnits'
                ],
                'CampaignsExternalCustomers',
                'Attachments'
            ]
        ]);
        $customer = $this->ExternalCustomers->Customers->get($externalCustomer->customer_id, [
            'fields'    => ['Customers.id', 'Customers.name'],
            'contain'   => [
                'BusinessUnits' => [
                    'Campaigns' => [
                        'conditions' => [
                            'Campaigns.customer_id' => $externalCustomer->customer_id,
                            'Campaigns.status' => 1
                        ],
                        'sort' => [ 'Campaigns.name ASC' ]
                    ],
                    'sort' => [ 'BusinessUnits.name ASC' ]
                ]
            ]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            unset($this->request->data['campaigns']);
            $externalCustomer = $this->ExternalCustomers->patchEntity($externalCustomer, $this->request->getData());
            if ($result = $this->ExternalCustomers->save($externalCustomer, ['associated' => ['CampaignsExternalCustomers', 'Attachments']])) {
                $this->Flash->success(__('The {0} has been saved.', 'External Customer'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'External Customer'));
            }
        }
        $accessGroups = $this->ExternalCustomers->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('externalCustomer', 'customer', 'accessGroups'));
        $this->set('_serialize', ['externalCustomer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id ExternalCustomer id.
     * @return \Cake\Http\Response
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $externalCustomer = $this->ExternalCustomers->get($id);
        try {
            if ($this->ExternalCustomers->delete($externalCustomer)) {
                $this->Flash->success(__('The {0} has been deleted.', 'External Customers'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'External Customers'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }

    /**
     * Archive method
     *
     * @param string|null $id ExternaCustomer id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function archive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $externalCustomer = $this->ExternalCustomers->get($id);
        if($this->ExternalCustomers->archiveUser($externalCustomer)){
            $this->Flash->success(__('The {0}: {1} has been archivated.', 'External Customer', $externalCustomer->email));
        } else {
            $this->Flash->error(__('The {0} could not be archivated. Please, try again.', 'External Customer'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }
}
