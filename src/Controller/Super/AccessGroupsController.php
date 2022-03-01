<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use App\Model\Table\AccessTypesActivitiesTable;
use Cake\Core\Exception\Exception;

/**
 * AccessGroups Controller
 *
 * @property \App\Model\Table\AccessGroupsTable $AccessGroups
 * @property \App\Controller\Component\SearchComponent $Search
 */
class AccessGroupsController extends AppController
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
            'order' => ['AccessGroups.name' => 'ASC']
        ];
        $accessGroups = $this->paginate($this->AccessGroups);
        $this->set(compact('accessGroups'));
        $this->set('_serialize', ['accessGroups']);
    }

    /**
     * View method
     *
     * @param string|null $id Access Group id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $accessGroup = $this->AccessGroups->get($id, [
            'contain' => ['IpsGroups', 'Users']
        ]);
        $this->set('accessGroup', $accessGroup);
        $this->set('_serialize', ['accessGroup']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $accessGroup = $this->AccessGroups->newEntity();
        if ($this->request->is('post')) {
            if($this->request->getData('all_ips') == 1){
                unset($this->request->data['ips_groups']);
            }
            $accessGroup = $this->AccessGroups->patchEntity($accessGroup, $this->request->getData());
            if ($result = $this->AccessGroups->save($accessGroup)) {
                $this->Flash->success(__('The {0} has been saved.', 'Access Group'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Access Group'));
            }
        }
        $this->set(compact('accessGroup'));
        $this->set('_serialize', ['accessGroup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Access Group id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $accessGroup = $this->AccessGroups->get($id, [
            'contain' => ['IpsGroups']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if($this->request->getData('all_ips') == 1){
                unset($this->request->data['ips_groups']);
            }
            $accessGroup = $this->AccessGroups->patchEntity($accessGroup, $this->request->getData());
            if ($this->AccessGroups->save($accessGroup)) {
                $this->Flash->success(__('The {0} has been saved.', 'Access Group'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Access Group'));
            }
        }
        $this->set(compact('accessGroup'));
        $this->set('_serialize', ['accessGroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Access Group id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $accessGroup = $this->AccessGroups->get($id);
        try {
            if ($this->AccessGroups->delete($accessGroup)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Access Group'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Access Group'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['action' => 'index']);
    }}
