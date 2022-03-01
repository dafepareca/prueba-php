<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * CustomerTypeIdentifications Controller
 *
 * @property \App\Model\Table\CustomerTypeIdentificationsTable $CustomerTypeIdentifications
 *
 * @method \App\Model\Entity\CustomerTypeIdentification[] paginate($object = null, array $settings = [])
 */
class CustomerTypeIdentificationsController extends AppController
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
            'order' => ['CustomerTypeIdentifications.id' => 'ASC']
        ];
        $customerTypeIdentifications = $this->paginate($this->CustomerTypeIdentifications);
        $this->set(compact('customerTypeIdentifications'));
        $this->set('_serialize', ['customerTypeIdentifications']);
    }

    /**
     * View method
     *
     * @param string|null $id Customer Type Identification id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customerTypeIdentification = $this->CustomerTypeIdentifications->get($id);

        $this->set('customerTypeIdentification', $customerTypeIdentification);
        $this->set('_serialize', ['customerTypeIdentification']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customerTypeIdentification = $this->CustomerTypeIdentifications->newEntity();
        if ($this->request->is('post')) {
            $customerTypeIdentification = $this->CustomerTypeIdentifications->patchEntity($customerTypeIdentification, $this->request->getData());
            if ($this->CustomerTypeIdentifications->save($customerTypeIdentification)) {
                $this->Flash->success(__('The {0} has been saved.', 'Customer Type Identification'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Customer Type Identification'));
            }
        }
        $this->set(compact('customerTypeIdentification'));
        $this->set('_serialize', ['customerTypeIdentification']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer Type Identification id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customerTypeIdentification = $this->CustomerTypeIdentifications->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customerTypeIdentification = $this->CustomerTypeIdentifications->patchEntity($customerTypeIdentification, $this->request->getData());
            if ($this->CustomerTypeIdentifications->save($customerTypeIdentification)) {
                $this->Flash->success(__('The {0} has been saved.', 'Customer Type Identification'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Customer Type Identification'));
            }
        }
        $this->set(compact('customerTypeIdentification'));
        $this->set('_serialize', ['customerTypeIdentification']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer Type Identification id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customerTypeIdentification = $this->CustomerTypeIdentifications->get($id);
        if ($this->CustomerTypeIdentifications->delete($customerTypeIdentification)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Customer Type Identification'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Customer Type Identification'));
        }
        return $this->redirect(['action' => 'index']);
    }}
