<?php
namespace App\Controller\Admin;

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
 * @property \App\Model\Table\CoordinadorTable $Coordinador
 * @property \App\Model\Table\BusinessTable $Business
 * @property \App\Model\Table\CustomerTypeIdentificationsTable $CustomerTypeIdentifications
 * @property \App\Controller\Component\EmailComponent $Email
 */
class CoordinadorController extends AppController{

    public $business = null;

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->loadModel('Business');
        $busines_id = $this->request->getQuery('busines_id');
        if(isset($busines_id) and !empty($busines_id)){
            $this->business = $this->Business->get($busines_id, [
                'fields' => [
                    'Business.id',
                    'Business.name'
                ],
            ]);
            $this->set('business', $this->business);
        }else{
            $this->Flash->error(__('No business selected. Please, try again.'));
            return $this->redirect(array_merge(['controller' => 'business', 'action' => 'index']));
        }
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
        $conditions['role_id in'] = [RolesTable::Coordinador];
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['AccessGroups', 'Attachments', 'UserStatuses', 'Roles'],
            'order' => ['Users.name' => 'ASC']
        ];
        $users = $this->paginate($this->Coordinador);
        $roles = $this->Coordinador->Roles->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $accessGroups = $this->Coordinador->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $userStatuses = $this->Coordinador->UserStatuses->find('list', ['limit' => 200, 'order' => 'id ASC']);
        $businessGroups = $this->Coordinador->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
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
        $user = $this->Coordinador->get($id, [
            'contain' => ['StateReasons','AccessGroups', 'Roles', 'Attachments', 'UserStatuses']
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
        $this->loadModel('CustomerTypeIdentifications');
        $user = $this->Coordinador->newEntity();
        if ($this->request->is('post')) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }

            $user = $this->Coordinador->patchEntity($user, $this->request->getData());
            $user->role_id = RolesTable::Coordinador;
            $user->user_status_id = UserStatusesTable::Active;
            $user->busines_id = $this->business->id;
            $token = strtoupper(substr(hash("sha512",rand(10000, 99999)),1, 6));
            $user->token = $token;
            $user->token_visible = $token;

            if ($result = $this->Coordinador->save($user)) {
                $this->Flash->success(__('The {0} has been saved.', 'Coordinador'));
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
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Administrator'));
            }
        }
        $accessGroups = $this->Coordinador->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessGroups = $this->Coordinador->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $typeIdentifications = $this->CustomerTypeIdentifications->find('list', ['limit' => 200, 'order' => 'type ASC']);
        $this->set(compact('user', 'accessGroups', 'businessGroups', 'typeIdentifications'));
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
        $this->loadModel('CustomerTypeIdentifications');
        $this->loadModel('UserStatuses');
        $this->loadModel('StateReasons');
        $user = $this->Coordinador->get($id, [
            'contain' => ['Attachments', 'UserStatuses']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            $user = $this->Coordinador->patchEntity($user, $this->request->getData());

            if($user->user_status_id == UserStatusesTable::Active){
                $user->state_reason_id = null;
            }
            if ($this->Coordinador->save($user)) {
                $this->Flash->success(__('The {0} has been saved.', 'Coordinador'));
                if($this->request->getData('all_user')){
                    return $this->redirect(['controller' => 'users','action' => 'index']);
                }else{
                    return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
                }
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Administrator'));
            }
        }
        $accessGroups = $this->Coordinador->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $businessGroups = $this->Coordinador->Business->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $typeIdentifications = $this->CustomerTypeIdentifications->find('list', ['limit' => 200, 'order' => 'type ASC']);
        $userStatuses = $this->UserStatuses->find('list', ['limit' => 200, 'order' => 'name ASC'])
            ->where(['id not in' => UserStatusesTable::Archived]);
        $stateReasons = $this->StateReasons->find('list', ['limit' => 200, 'order' => 'state ASC']);
        $this->set(compact('user', 'accessGroups', 'businessGroups', 'typeIdentifications','userStatuses','stateReasons'));
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
        $user = $this->Coordinador->get($id);
        if ($this->Coordinador->delete($user)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Administrator'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Administrator'));
        }
        if($this->request->getQuery('all_user')){
            return $this->redirect(['controller' => 'users','action' => 'index']);
        }else{
            return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
        }

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
        $user = $this->Coordinador->get($id);
        if($this->Coordinador->archiveUser($user)){
            $this->Flash->success(__('The {0}: {1} has been archivated.', 'Coordinador', $user->email));
        } else {
            $this->Flash->error(__('The {0} could not be archivated. Please, try again.', 'Coordinador'));
        }
        if($this->request->getQuery('all_user')){
            return $this->redirect(['controller' => 'users','action' => 'index']);
        }else{
            return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
        }

    }

    public function reset_password($id){
        $this->request->allowMethod(['post']);
        $user = $this->Coordinador->get($id);
        $user = $this->Coordinador->patchEntity($user, []);

        $token = $user->identification;
        $user->token = $token;
        $user->token_visible = $token;

        $date = date('Y-m-d');
        $newDate = strtotime ( '-1 day' , strtotime ( $date )) ;
        $newDate = date ( 'Y-m-d' , $newDate );
        $user->password_expiration_date = $newDate;

        if ($result = $this->Coordinador->save($user)) {
            $this->Flash->success(__('password reset'));
        } else {
            $this->Flash->error(__('password no reset'));
        }
        if($this->request->getData('all_user')){
            return $this->redirect(['controller' => 'users','action' => 'index']);
        }else{
            return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
        }
    }

}
