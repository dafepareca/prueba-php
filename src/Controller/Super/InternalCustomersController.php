<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use App\Model\Table\RolesTable;
use App\Model\Table\UserStatusesTable;
use Cake\Event\Event;

/**
 * InternalCustomers Controller
 *
 * @property \App\Model\Table\InternalCustomersTable $InternalCustomers
 */
class InternalCustomersController extends AppController{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->setConfig('unlockedActions', ['add', 'edit']);
    }
    /**
     * View method
     *
     * @param string|null $id InternalCustomer id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $internalCustomer = $this->InternalCustomers->get($id, [
            'contain' => ['AccessGroups', 'Roles', 'Attachments', 'UserStatuses']
        ]);
        $this->set('internalCustomer', $internalCustomer);
        $this->set('_serialize', ['internalCustomer']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $internalCustomer = $this->InternalCustomers->newEntity();
        if ($this->request->is('post')) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            $internalCustomer = $this->InternalCustomers->patchEntity($internalCustomer, $this->request->getData());
            $internalCustomer->role_id = RolesTable::Internal;
            $internalCustomer->user_status_id = UserStatusesTable::Active;
            unset($this->request->data['campaigns']);
            if ($result = $this->InternalCustomers->save($internalCustomer)) {
                $this->Flash->success(__('The {0} has been saved.', 'Internal Customers'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Internal Customers'));
            }
        }
        $accessGroups = $this->InternalCustomers->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $campaigns = $this->InternalCustomers->Campaigns
            ->find('list', ['groupField' => "business_unit.name"]) // agrupar por el nombre de la tabla
            ->select(['Campaigns.id', 'Campaigns.name', 'Campaigns.business_unit_id', 'BusinessUnits.name'])
            ->where(['Campaigns.status ' => 1])
            ->contain('BusinessUnits')
            ->order(['BusinessUnits.name ASC', 'Campaigns.name ASC']);
        $businessUnits = $this->InternalCustomers->CampaignsInternalCustomers->Campaigns->BusinessUnits->find('all')
            ->select(['BusinessUnits.id', 'BusinessUnits.name', 'BusinessUnits.campaign_count'])
            ->contain(['Campaigns' => [
                'fields' => [ 'Campaigns.id', 'Campaigns.name', 'Campaigns.business_unit_id' ],
                'sort' => [ 'Campaigns.name ASC' ],
                'conditions' => [
                    'Campaigns.status' => 1
                ]
            ]])
            ->where(['BusinessUnits.campaign_count > 0'])
            ->order(['BusinessUnits.name ASC']);

        $this->set(compact('internalCustomer', 'businessUnits', 'campaigns', 'accessGroups'));
        $this->set('_serialize', ['internalCustomer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id InternalCustomer id.
     * @return \Cake\Http\Response
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $internalCustomer = $this->InternalCustomers->get($id, [
            'contain' => [
                'Campaigns' => [
                    'BusinessUnits'
                ],
                'CampaignsInternalCustomers',
                'Attachments'
            ]
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            unset($this->request->data['campaigns']);
            $internalCustomer = $this->InternalCustomers->patchEntity($internalCustomer, $this->request->getData());
            if ($this->InternalCustomers->save($internalCustomer, ['associated' => ['CampaignsInternalCustomers', 'Attachments']])) {
                $this->Flash->success(__('The {0} has been saved.', 'Internal Customer'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Internal Customer'));
            }
        }
        $accessGroups = $this->InternalCustomers->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessUnits = $this->InternalCustomers->CampaignsInternalCustomers->Campaigns->BusinessUnits->find('all')
            ->select(['BusinessUnits.id', 'BusinessUnits.name', 'BusinessUnits.campaign_count'])
            ->contain(['Campaigns' => [
                'fields' => [ 'Campaigns.id', 'Campaigns.name', 'Campaigns.business_unit_id' ],
                'sort' => [ 'Campaigns.name ASC' ],
                'conditions' => [
                    'Campaigns.status' => 1
                ]
            ]])
            ->where('BusinessUnits.campaign_count > 0')
            ->order(['BusinessUnits.name ASC']);
        $this->set(compact('internalCustomer', 'businessUnits', 'campaigns', 'accessGroups'));
        $this->set('_serialize', ['internalCustomer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id InternalCustomer id.
     * @return \Cake\Http\Response
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $internalCustomer = $this->InternalCustomers->get($id);
        try {
            if ($this->InternalCustomers->delete($internalCustomer)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Internal Customers'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Internal Customers'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }

    /**
     * Archive method
     *
     * @param string|null $id InternaCustomer id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function archive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $internalCustomer = $this->InternalCustomers->get($id);
        if($this->InternalCustomers->archiveUser($internalCustomer)){
            $this->Flash->success(__('The {0}: {1} has been archivated.', 'Internal Customer', $internalCustomer->email));
        } else {
            $this->Flash->error(__('The {0} could not be archivated. Please, try again.', 'Internal Customer'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }
}
