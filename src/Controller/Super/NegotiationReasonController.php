<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * NegotiationReason Controller
 *
 * @property \App\Model\Table\NegotiationReasonTable $NegotiationReason
 *
 * @method \App\Model\Entity\NegotiationReason[] paginate($object = null, array $settings = [])
 */
class NegotiationReasonController extends AppController
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
            'order' => ['NegotiationReason.id' => 'ASC']
        ];
        $negotiationReason = $this->paginate($this->NegotiationReason);
        $this->set(compact('negotiationReason'));
        $this->set('_serialize', ['negotiationReason']);
    }

    /**
     * View method
     *
     * @param string|null $id Negotiation Reason id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $negotiationReason = $this->NegotiationReason->get($id, [
            'contain' => []
        ]);

        $this->set('negotiationReason', $negotiationReason);
        $this->set('_serialize', ['negotiationReason']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $negotiationReason = $this->NegotiationReason->newEntity();
        if ($this->request->is('post')) {
            $negotiationReason = $this->NegotiationReason->patchEntity($negotiationReason, $this->request->getData());
            if ($this->NegotiationReason->save($negotiationReason)) {
                $this->Flash->success(__('The {0} has been saved.', 'Negotiation Reason'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Negotiation Reason'));
            }
        }
        $this->set(compact('negotiationReason'));
        $this->set('_serialize', ['negotiationReason']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Negotiation Reason id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $negotiationReason = $this->NegotiationReason->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $negotiationReason = $this->NegotiationReason->patchEntity($negotiationReason, $this->request->getData());
            if ($this->NegotiationReason->save($negotiationReason)) {
                $this->Flash->success(__('The {0} has been saved.', 'Negotiation Reason'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Negotiation Reason'));
            }
        }
        $this->set(compact('negotiationReason'));
        $this->set('_serialize', ['negotiationReason']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Negotiation Reason id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $negotiationReason = $this->NegotiationReason->get($id);
        if ($this->NegotiationReason->delete($negotiationReason)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Negotiation Reason'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Negotiation Reason'));
        }
        return $this->redirect(['action' => 'index']);
    }}
