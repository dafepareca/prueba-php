<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use App\Model\Table\RolesTable;
use App\Model\Table\UserStatusesTable;
use Cake\Event\Event;

/**
 * Managers Controller
 *
 * @property \App\Model\Table\UnitManagersTable $UnitManagers
 */
class UnitManagersController extends AppController{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->setConfig('unlockedActions', ['add', 'edit']);
    }
    /**
     * View method
     *
     * @param string|null $id UnitManager id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $unitManager = $this->UnitManagers->get($id, [
            'contain' => ['BusinessUnits', 'AccessGroups', 'Attachments', 'Roles', 'UserStatuses']
        ]);
        $this->set('unitManager', $unitManager);
        $this->set('_serialize', ['unitManager']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $unitManager = $this->UnitManagers->newEntity();
        if ($this->request->is('post')) {
            if(empty($this->request->data['attachment']['photo'])){
                unset($this->request->data['attachment']);
            }
            $unitManager = $this->UnitManagers->patchEntity($unitManager, $this->request->getData());
            $unitManager->role_id = RolesTable::Manager;
            $unitManager->user_status_id = UserStatusesTable::Active;
            if ($result = $this->UnitManagers->save($unitManager)) {
                $this->Flash->success(__('The {0} has been saved.', 'Unit Manager'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Unit Manager'));
            }
        }
        $businessUnits = $this->UnitManagers->BusinessUnits->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $accessGroups = $this->UnitManagers->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('unitManager', 'businessUnits', 'accessGroups'));
        $this->set('_serialize', ['unitManager']);
    }

    /**
     * Edit method
     *
     * @param string|null $id UnitManager id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $unitManager = $this->UnitManagers->get($id, [
            'contain' => ['BusinessUnits', 'Attachments']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            $unitManager = $this->UnitManagers->patchEntity($unitManager, $this->request->getData());
            if ($this->UnitManagers->save($unitManager)) {
                $this->Flash->success(__('The {0} has been saved.', 'Unit Manager'));
                return $this->redirect(['controller' => 'Users', 'action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Unit Manager'));
            }
        }
        $businessUnits = $this->UnitManagers->BusinessUnits->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $accessGroups = $this->UnitManagers->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('unitManager', 'businessUnits', 'accessGroups'));
        $this->set('_serialize', ['unitManager']);
    }

    /**
     * Delete method
     *
     * @param string|null $id UnitManager id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $unitManager = $this->UnitManagers->get($id);
        try {
            if ($this->UnitManagers->delete($unitManager)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Unit Manager'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Unit Manager'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }

    /**
     * Archive method
     *
     * @param string|null $id UnitManager id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function archive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $unitManager = $this->UnitManagers->get($id);
        if($this->UnitManagers->archiveUser($unitManager)){
            $this->Flash->success(__('The {0}: {1} has been archivated.', 'Unit Manager', $unitManager->email));
        } else {
            $this->Flash->error(__('The {0} could not be archivated. Please, try again.', 'Unit Manager'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }
}
