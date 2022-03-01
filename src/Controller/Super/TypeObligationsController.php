<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * TypeObligations Controller
 *
 * @property \App\Model\Table\TypeObligationsTable $TypeObligations
 *
 * @method \App\Model\Entity\TypeObligation[] paginate($object = null, array $settings = [])
 */
class TypeObligationsController extends AppController
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
            'order' => ['TypeObligations.id' => 'ASC']
        ];
        $typeObligations = $this->paginate($this->TypeObligations);
        $this->set(compact('typeObligations'));
        $this->set('_serialize', ['typeObligations']);
    }

    /**
     * View method
     *
     * @param string|null $id Type Obligation id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $typeObligation = $this->TypeObligations->get($id, [
            'contain' => []
        ]);

        $this->set('typeObligation', $typeObligation);
        $this->set('_serialize', ['typeObligation']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $typeObligation = $this->TypeObligations->newEntity();
        if ($this->request->is('post')) {
            $typeObligation = $this->TypeObligations->patchEntity($typeObligation, $this->request->getData());
            if ($this->TypeObligations->save($typeObligation)) {
                $this->Flash->success(__('The {0} has been saved.', 'Type Obligation'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Type Obligation'));
            }
        }
        $this->set(compact('typeObligation'));
        $this->set('_serialize', ['typeObligation']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Type Obligation id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $typeObligation = $this->TypeObligations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $typeObligation = $this->TypeObligations->patchEntity($typeObligation, $this->request->getData());
            if ($this->TypeObligations->save($typeObligation)) {
                $this->Flash->success(__('The {0} has been saved.', 'Type Obligation'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Type Obligation'));
            }
        }
        $this->set(compact('typeObligation'));
        $this->set('_serialize', ['typeObligation']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Type Obligation id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $typeObligation = $this->TypeObligations->get($id);
        if ($this->TypeObligations->delete($typeObligation)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Type Obligation'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Type Obligation'));
        }
        return $this->redirect(['action' => 'index']);
    }}
