<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use App\Model\Table\RolesTable;
use App\Model\Table\UserStatusesTable;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\BusinessTable $Business
 * @property \App\Controller\Component\SearchComponent $Search
 */

class UsersController extends AppController{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->setConfig('unlockedActions', ['add', 'edit']);
    }

    /**
     * dashboard method
     *
     * @return \Cake\Network\Response|null Redirects to index.
     */
    public function dashboard()
    {
        $accessLogs = $this->Users->AccessLogs->find()
            ->select([
                'AccessLogs.date_login',
                'AccessLogs.user_id',
                'Users.name',
                'Users.role_id',
                'Roles.name',
                'Attachments.model',
                'Attachments.file',
                'Attachments.photo',
                'Attachments.file_dir',
                'LogoutsTypes.name'
            ])
            ->order(['AccessLogs.date_login' => 'DESC'])
            //->group('AccessLogs.user_id')
            ->contain([
                'Users' => [
                    'Roles',
                    'Attachments'
                ],
                'LogoutsTypes'
            ])
            ->limit(8);
        $this->set(compact('accessLogs'));
        $this->set('_serialize', ['accessLogs']);
    }

    /**
     * Index method
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->loadComponent('Search');
        $this->loadModel('Business');
        $conditions = $this->Search->getConditions();
        #$conditions['role_id in'] = [RolesTable::Admin,RolesTable::Super];
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['AccessGroups', 'Attachments', 'UserStatuses', 'Roles', 'Business'],
            'order' => ['Users.name' => 'ASC']
        ];
        $users = $this->paginate($this->Users);
        $roles = $this->Users->Roles->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $business = $this->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $accessGroups = $this->Users->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessGroups = $this->Users->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $userStatuses = $this->Users->UserStatuses->find('list', ['limit' => 200, 'order' => 'id ASC']);
        $this->set(compact('users', 'accessGroups', 'businessGroups', 'userStatuses', 'roles', 'business'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['AccessGroups', 'Roles', 'Attachments', 'UserStatuses']
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->role_id = RolesTable::Super;
            $user->user_status_id = UserStatusesTable::Active;

            $token = $user->identification;
            $user->token = $token;
            $user->token_visible = $token;

            $date = date('Y-m-d');
            $newDate = strtotime ( '-1 day' , strtotime ( $date )) ;
            $newDate = date ( 'Y-m-d' , $newDate );
            $user->password_expiration_date = $newDate;

            if ($result = $this->Users->save($user)) {
                $this->Flash->success(__('The {0} has been saved.', 'Super Administrator'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Super Administrator'));
            }
        }
        $accessGroups = $this->Users->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessGroups = $this->Users->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('user', 'accessGroups', 'businessGroups'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Attachments', 'UserStatuses']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The {0} has been saved.', 'Administrator'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Administrator'));
            }
        }
        $accessGroups = $this->Users->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessGroups = $this->Users->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('user', 'accessGroups', 'businessGroups'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        try {
            if ($this->Users->delete($user)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Administrator'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Administrator'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Archive method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function archive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if($this->Users->archiveUser($user)){
            $this->Flash->success(__('The {0}: {1} has been archivated.', 'Administrator', $user->email));
        } else {
            $this->Flash->error(__('The {0} could not be archivated. Please, try again.', 'Administrator'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }

    public function reset_password($id){
        $this->request->allowMethod(['post']);
        $user = $this->Users->get($id);
        $user = $this->Users->patchEntity($user, []);

        $token = $user->identification;
        $user->token = $token;
        $user->token_visible = $token;

        $date = date('Y-m-d');
        $newDate = strtotime ( '-1 day' , strtotime ( $date )) ;
        $newDate = date ( 'Y-m-d' , $newDate );
        $user->password_expiration_date = $newDate;

        if ($result = $this->Users->save($user)) {
            $this->Flash->success(__('password reset'));
            return $this->redirect(array_merge(['controller'=>'Users','action' => 'index'], $this->request->getQuery()));
        } else {
            $this->Flash->error(__('password no reset'));
        }
        return $this->redirect(array_merge(['controller'=>'Users','action' => 'index'], $this->request->getQuery()));
    }
}
