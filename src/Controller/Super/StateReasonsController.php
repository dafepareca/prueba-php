<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * StateReasons Controller
 *
 * @property \App\Model\Table\StateReasonsTable $StateReasons
 *
 * @method \App\Model\Entity\StateReason[] paginate($object = null, array $settings = [])
 */
class StateReasonsController extends AppController
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
            'order' => ['StateReasons.id' => 'ASC']
        ];
        $stateReasons = $this->paginate($this->StateReasons);
        $this->set(compact('stateReasons'));
        $this->set('_serialize', ['stateReasons']);
    }

    /**
     * View method
     *
     * @param string|null $id State Reason id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stateReason = $this->StateReasons->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('stateReason', $stateReason);
        $this->set('_serialize', ['stateReason']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stateReason = $this->StateReasons->newEntity();
        if ($this->request->is('post')) {
            $stateReason = $this->StateReasons->patchEntity($stateReason, $this->request->getData());
            if ($this->StateReasons->save($stateReason)) {
                $this->Flash->success(__('The {0} has been saved.', 'State Reason'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'State Reason'));
            }
        }
        $this->set(compact('stateReason'));
        $this->set('_serialize', ['stateReason']);
    }

    /**
     * Edit method
     *
     * @param string|null $id State Reason id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $stateReason = $this->StateReasons->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stateReason = $this->StateReasons->patchEntity($stateReason, $this->request->getData());
            if ($this->StateReasons->save($stateReason)) {
                $this->Flash->success(__('The {0} has been saved.', 'State Reason'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'State Reason'));
            }
        }
        $this->set(compact('stateReason'));
        $this->set('_serialize', ['stateReason']);
    }

    /**
     * Delete method
     *
     * @param string|null $id State Reason id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $stateReason = $this->StateReasons->get($id);
        if ($this->StateReasons->delete($stateReason)) {
            $this->Flash->success(__('The {0} has been deleted.', 'State Reason'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'State Reason'));
        }
        return $this->redirect(['action' => 'index']);
    }}
