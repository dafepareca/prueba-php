<?php
namespace App\Controller\Coordinador;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Network\Exception\NotFoundException;
use Cake\Auth\DefaultPasswordHasher;
use App\Model\Table\RolesTable;
use App\Model\Table\UserStatusesTable;
use App\Model\Table\AccessTypesActivitiesTable;

/**
 * Users Controller
 *
 * @property \App\Model\Table\AsesorTable $Asesor
 * @property \App\Controller\Component\EmailComponent $Email
 */
class AsesorController extends AppController{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * dashboard method
     *
     * @return \Cake\Network\Response|null Redirects to index.
     */
    public function dashboard()
    {

    }

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
            'contain' => ['AccessGroups', 'Attachments', 'UserStatuses', 'Roles'],
            'order' => ['Users.name' => 'ASC']
        ];
        $users = $this->paginate($this->Asesor);
        $roles = $this->Asesor->Roles->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $accessGroups = $this->Asesor->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessGroups = $this->Asesor->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $userStatuses = $this->Asesor->UserStatuses->find('list', ['limit' => 200, 'order' => 'id ASC']);
        $this->set(compact('users', 'accessGroups', 'businessGroups', 'userStatuses', 'roles'));
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
        $user = $this->Asesor->get($id, [
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
        $user = $this->Asesor->newEntity();
        if ($this->request->is('post')) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }

            $user = $this->Asesor->patchEntity($user, $this->request->getData());
            $user->role_id = RolesTable::Asesor;
            $user->user_status_id = UserStatusesTable::Active;

            $token = strtoupper(substr(hash("sha512",rand(10000, 99999)),1, 6));

            $user->token = (new DefaultPasswordHasher)->hash($token);
            if ($result = $this->Asesor->save($user)) {
                $this->Flash->success(__('The {0} has been saved.', 'Asesor'));
                $this->loadComponent('Email');

                $this->Email->add($this->request->getData(['email']), $this->request->getData(['name']));
                $this->Email->send(
                    __('Clave de acceso Dataweb'),
                    'default',
                    'envio_clave',
                    [
                        'mensaje' => __('Su datos de acceso al sistema Dataweb son :'),
                        'usuario' => $this->request->getData(['email']),
                        'clave' => $token,
                        'nombre' => $this->request->getData('name'),
                        'link' => "http://".$_SERVER['HTTP_HOST'].'/'
                    ]
                );

                return $this->redirect(['controller' => 'Users','action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Administrator'));
            }
        }
        $accessGroups = $this->Asesor->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessGroups = $this->Asesor->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
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
        $user = $this->Asesor->get($id, [
            'contain' => ['Attachments', 'UserStatuses']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            $user = $this->Asesor->patchEntity($user, $this->request->getData());
            if ($this->Asesor->save($user)) {
                $this->Flash->success(__('The {0} has been saved.', 'Administrator'));
                return $this->redirect(['controller' => 'Users','action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Administrator'));
            }
        }
        $accessGroups = $this->Asesor->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessGroups = $this->Asesor->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
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
        $user = $this->Asesor->get($id);
        if ($this->Asesor->delete($user)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Administrator'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Administrator'));
        }
        return $this->redirect(['controller' => 'Users','action' => 'index']);
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
        $user = $this->Asesor->get($id);
        if($this->Asesor->archiveUser($user)){
            $this->Flash->success(__('The {0}: {1} has been archivated.', 'Administrator', $user->email));
        } else {
            $this->Flash->error(__('The {0} could not be archivated. Please, try again.', 'Administrator'));
        }
        return $this->redirect(['controller' => 'Users', 'action' => 'index']);
    }

}
