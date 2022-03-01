<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;

/**
 * AccessLogs Controller
 *
 * @property \App\Model\Table\AccessLogsTable $AccessLogs
 * @property \App\Controller\Component\SearchComponent $Search
 */
class AccessLogsController extends AppController
{

    /**
    * Index method
    * @return \Cake\Network\Response|null
    */
    public function index()
    {
        $data = $this->request->getQuery();
        if(empty($data['date_login'])){
            $this->request->data['date_login'] = date('Y-m-d').' - '.date('Y-m-d');
            $this->request->query['date_login'] = date('Y-m-d').' - '.date('Y-m-d');
        }
        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [
                'Users' => [
                    'Roles'
                ],
                'LogoutsTypes'
            ],
            'order' => ['AccessLogs.date_login' => 'DESC']
        ];
        $accessLogs = $this->paginate($this->AccessLogs);

        $roles = $this->AccessLogs->Users->Roles->find('list', ['limit' => 200, 'order' => 'name ASC']);


        $this->set(compact('accessLogs', 'roles'));
        $this->set('_serialize', ['accessLogs']);
    }

    /**
     * View method
     *
     * @param string|null $id Access Log id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $accessLog = $this->AccessLogs->get($id, [
            'contain' => [
                'Users' => [
                    'Roles',
                    'Attachments',
                ],
                'Audits' => [
                    'sort' => [
                        'Audits.id' => 'DESC'
                    ]
                ]
            ]
        ]);

        $this->set('accessLog', $accessLog);
        $this->set('_serialize', ['accessLog']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $accessLog = $this->AccessLogs->newEntity();
        if ($this->request->is('post')) {
            $accessLog = $this->AccessLogs->patchEntity($accessLog, $this->request->getData());
            if ($this->AccessLogs->save($accessLog)) {
                $this->Flash->success(__('The {0} has been saved.', 'Access Log'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Access Log'));
            }
        }
        $users = $this->AccessLogs->Users->find('list', ['limit' => 200]);
        $this->set(compact('accessLog', 'users'));
        $this->set('_serialize', ['accessLog']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Access Log id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $accessLog = $this->AccessLogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $accessLog = $this->AccessLogs->patchEntity($accessLog, $this->request->getData());
            if ($this->AccessLogs->save($accessLog)) {
                $this->Flash->success(__('The {0} has been saved.', 'Access Log'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Access Log'));
            }
        }
        $users = $this->AccessLogs->Users->find('list', ['limit' => 200]);
        $this->set(compact('accessLog', 'users'));
        $this->set('_serialize', ['accessLog']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Access Log id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $accessLog = $this->AccessLogs->get($id);
        try {
            if ($this->AccessLogs->delete($accessLog)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Access Log'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Access Log'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['action' => 'index']);
    }}
