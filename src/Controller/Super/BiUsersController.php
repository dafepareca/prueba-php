<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Network\Exception\NotFoundException;
use Cake\Auth\DefaultPasswordHasher;
use App\Model\Table\RolesTable;
use App\Model\Table\UserStatusesTable;
use App\Model\Table\AccessTypesActivitiesTable;

/**
 * BiUsers Controller
 *
 * @property \App\Model\Table\BiUsersTable $BiUsers
 */
class BiUsersController extends AppController{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->setConfig('unlockedActions', ['add', 'edit']);
    }

    /**
     * View method
     *
     * @param string|null $id BiUser id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $biUser = $this->BiUsers->get($id, [
            'contain' => ['AccessGroups', 'Roles', 'Attachments', 'UserStatuses']
        ]);

        $this->set('biUser', $biUser);
        $this->set('_serialize', ['biUser']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $biUser = $this->BiUsers->newEntity();
        if ($this->request->is('post')) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            $biUser = $this->BiUsers->patchEntity($biUser, $this->request->getData());
            $biUser->role_id = RolesTable::Bi;
            $biUser->user_status_id = UserStatusesTable::Active;
            unset($this->request->data['campaigns']);
            if ($result = $this->BiUsers->save($biUser)) {
                $this->Flash->success(__('The {0} has been saved.', 'Bi User'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Bi User'));
            }
        }
        $accessGroups = $this->BiUsers->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $campaigns = $this->BiUsers->Campaigns
            ->find('list', ['groupField' => "business_unit.name"]) // agrupar por el nombre de la tabla
            ->select(['Campaigns.id', 'Campaigns.name', 'Campaigns.business_unit_id', 'BusinessUnits.name'])
            ->where(['Campaigns.status' => 1])
            ->contain('BusinessUnits')
            ->order(['BusinessUnits.name ASC', 'Campaigns.name ASC']);
        $businessUnits = $this->BiUsers->CampaignsBiUsers->Campaigns->BusinessUnits->find('all')
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
        $this->set(compact('biUser', 'businessUnits', 'campaigns', 'accessGroups'));
        $this->set('_serialize', ['biUser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id BiUser id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $biUser = $this->BiUsers->get($id, [
            'contain' => ['Attachments', 'Campaigns' => [ 'BusinessUnits'], 'CampaignsBiUsers']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            unset($this->request->data['campaigns']);

            $biUser = $this->BiUsers->patchEntity($biUser, $this->request->getData());
            if ($this->BiUsers->save($biUser, ['associated' => ['CampaignsBiUsers', 'Attachments']])) {
                $this->Flash->success(__('The {0} has been saved.', 'Bi User'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Bi User'));
            }
        }
        $accessGroups = $this->BiUsers->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessUnits = $this->BiUsers->CampaignsBiUsers->Campaigns->BusinessUnits->find('all')
            ->select(['BusinessUnits.id', 'BusinessUnits.name', 'BusinessUnits.campaign_count'])
            ->contain([
                'Campaigns' => [
                    'fields' => [ 'Campaigns.id', 'Campaigns.name', 'Campaigns.business_unit_id' ],
                    'sort' => [ 'Campaigns.name ASC' ],
                    'conditions' => [
                        'Campaigns.status' => 1
                    ]
                ]
            ])
            ->where('BusinessUnits.campaign_count > 0')
            ->order(['BusinessUnits.name ASC']);
        $this->set(compact('biUser', 'businessUnits', 'campaigns', 'accessGroups'));
        $this->set('_serialize', ['biUser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id BiUser id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $biUser = $this->BiUsers->get($id);
        try {
            if ($this->BiUsers->delete($biUser)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Bi User'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Bi User'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }

    /**
     * Archive method
     *
     * @param string|null $id BiUser id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function archive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $biUser = $this->BiUsers->get($id);
        if($this->BiUsers->archiveUser($biUser)){
            $this->Flash->success(__('The {0}: {1} has been archivated.', 'Bi User', $biUser->email));
        } else {
            $this->Flash->error(__('The {0} could not be archivated. Please, try again.', 'Bi User'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }
}