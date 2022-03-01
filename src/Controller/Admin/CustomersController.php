<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Controller\Component\SearchComponent $Search
 */
class CustomersController extends AppController
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
            'order' => ['Customers.name' => 'ASC']
        ];
        $customers = $this->paginate($this->Customers);
        $this->set(compact('customers'));
        $this->set('_serialize', ['customers']);
    }

    /**
     * View method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => ['BusinessUnits']
        ]);
        $this->set('customer', $customer);
        $this->set('_serialize', ['customer']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $customer = $this->Customers->newEntity();
        if ($this->request->is('post')) {
            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
            if ($result = $this->Customers->save($customer)) {
                $this->Flash->success(__('The {0} has been saved.', 'Customer'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Customer'));
            }
        }
        $businessUnits = $this->Customers->BusinessUnits->find('list', ['limit' => 200, 'order' => 'name ASC']);
        $this->set(compact('customer', 'businessUnits'));
        $this->set('_serialize', ['customer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $customer = $this->Customers->get($id, [
            'contain' => ['BusinessUnits']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
            if ($this->Customers->save($customer)) {
                $this->Flash->success(__('The {0} has been saved.', 'Customer'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Customer'));
            }
        }
        $businessUnits = $this->Customers->BusinessUnits->find('list', ['limit' => 200]);
        $this->set(compact('customer', 'businessUnits'));
        $this->set('_serialize', ['customer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer id.
     * @return \Cake\Network\Response|null Redirects to index.

     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $customer = $this->Customers->get($id);
        try {
            if ($this->Customers->delete($customer)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Customer'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Customer'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['action' => 'index']);
    }

}
