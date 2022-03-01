<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use App\Controller\Component\searchComponent;
use Cake\Core\Exception\Exception;

/**
 * AccessActivities Controller
 *
 * @property \App\Model\Table\AccessActivitiesTable $AccessActivities
 * @property \App\Controller\Component\SearchComponent $Search
 */
class AccessActivitiesController extends AppController
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
            'contain' => [
                'AccessLogs' => [
                    'Users' => [
                        'Roles'
                    ]
                ],
                'AccessTypesActivities'
            ],
            'order' => ['AccessActivities.date' => 'DESC'],
        ];



        $accessActivities = $this->paginate($this->AccessActivities);
        $accessTypesActivities = $this->AccessActivities->AccessTypesActivities->find('list', [
            'keyField' => 'id',
            'valueField' => 'type',
            'limit' => 200,
            'order' => 'type'
        ]);
        $models = $this->AccessActivities->find('list', [
            'keyField' => 'model',
            'valueField' => 'model',
            'limit' => 200,
            'group' => 'model',
            'order' => 'model'
        ]);
        $this->set(compact('accessActivities', 'accessTypesActivities', 'models'));
        $this->set('_serialize', ['accessActivities']);
    }

    /**
     * View method
     *
     * @param string|null $id Access Activity id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $accessActivity = $this->AccessActivities->get($id, [
            'contain' => ['AccessLogs', 'AccessTypesActivities']
        ]);

        $this->set('accessActivity', $accessActivity);
        $this->set('_serialize', ['accessActivity']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $accessActivity = $this->AccessActivities->newEntity();
        if ($this->request->is('post')) {
            $accessActivity = $this->AccessActivities->patchEntity($accessActivity, $this->request->getData());
            if ($this->AccessActivities->save($accessActivity)) {
                $this->Flash->success(__('The {0} has been saved.', 'Access Activity'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Access Activity'));
            }
        }
        $accessLogs = $this->AccessActivities->AccessLogs->find('list', ['limit' => 200]);
        $accessTypesActivities = $this->AccessActivities->AccessTypesActivities->find('list', ['limit' => 200]);
        $this->set(compact('accessActivity', 'accessLogs', 'accessTypesActivities'));
        $this->set('_serialize', ['accessActivity']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Access Activity id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $accessActivity = $this->AccessActivities->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $accessActivity = $this->AccessActivities->patchEntity($accessActivity, $this->request->getData());
            if ($this->AccessActivities->save($accessActivity)) {
                $this->Flash->success(__('The {0} has been saved.', 'Access Activity'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Access Activity'));
            }
        }
        $accessLogs = $this->AccessActivities->AccessLogs->find('list', ['limit' => 200]);
        $accessTypesActivities = $this->AccessActivities->AccessTypesActivities->find('list', ['limit' => 200]);
        $this->set(compact('accessActivity', 'accessLogs', 'accessTypesActivities'));
        $this->set('_serialize', ['accessActivity']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Access Activity id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $accessActivity = $this->AccessActivities->get($id);
        try {
            if ($this->AccessActivities->delete($accessActivity)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Access Activity'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Access Activity'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['action' => 'index']);
    }}
