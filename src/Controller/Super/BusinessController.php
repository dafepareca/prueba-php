<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * Business Controller
 *
 * @property \App\Model\Table\BusinessTable $Business
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\Busines[] paginate($object = null, array $settings = [])
 */
class BusinessController extends AppController
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
            'contain' => [],
            'order' => ['Business.id' => 'ASC']
        ];
        $business = $this->paginate($this->Business);
        $this->set(compact('business'));
        $this->set('_serialize', ['business']);
    }

    /**
     * View method
     *
     * @param string|null $id Busines id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $busines = $this->Business->get($id, [
            'contain' => []
        ]);

        $this->set('busines', $busines);
        $this->set('_serialize', ['busines']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->loadModel('Users');
        $busines = $this->Business->newEntity();
        if ($this->request->is('post')) {
            $busines = $this->Business->patchEntity($busines, $this->request->getData());
            if ($this->Business->save($busines)) {
                $this->Flash->success(__('The {0} has been saved.', 'Busines'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Busines'));
            }
        }
        $accessGroups = $this->Users->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('busines','accessGroups'));
        $this->set('_serialize', ['busines']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Busines id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Users');
        $busines = $this->Business->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $busines = $this->Business->patchEntity($busines, $this->request->getData());
            if ($this->Business->save($busines)) {
                $this->Flash->success(__('The {0} has been saved.', 'Busines'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Busines'));
            }
        }
        $accessGroups = $this->Users->AccessGroups->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('busines','accessGroups'));
        $this->set('_serialize', ['busines']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Busines id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $busines = $this->Business->get($id);
        if ($this->Business->delete($busines)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Busines'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Busines'));
        }
        return $this->redirect(['action' => 'index']);
    }}
