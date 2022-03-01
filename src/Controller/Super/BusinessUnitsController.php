<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use App\Model\Table\AccessTypesActivitiesTable;

/**
 * BusinessUnits Controller
 *
 * @property \App\Model\Table\BusinessUnitsTable $BusinessUnits
 * @property \App\Controller\Component\SearchComponent $Search
 */
class BusinessUnitsController extends AppController
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
            'order' => ['BusinessUnits.name' => 'ASC']
        ];
        $businessUnits = $this->paginate($this->BusinessUnits);
        $this->set(compact('businessUnits'));
        $this->set('_serialize', ['businessUnits']);
    }

    /**
     * View method
     *
     * @param string|null $id Business Unit id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $businessUnit = $this->BusinessUnits->get($id, [
            'contain' => []
        ]);
        $this->set('businessUnit', $businessUnit);
        $this->set('_serialize', ['businessUnit']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $businessUnit = $this->BusinessUnits->newEntity();
        if ($this->request->is('post')) {
            $businessUnit = $this->BusinessUnits->patchEntity($businessUnit, $this->request->getData());
            if ($result = $this->BusinessUnits->save($businessUnit)) {
                $this->Flash->success(__('The {0} has been saved.', 'Business Unit'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Business Unit'));
            }
        }
        $this->set(compact('businessUnit'));
        $this->set('_serialize', ['businessUnit']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Business Unit id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $businessUnit = $this->BusinessUnits->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $businessUnit = $this->BusinessUnits->patchEntity($businessUnit, $this->request->getData());
            if ($this->BusinessUnits->save($businessUnit)) {
                $this->Flash->success(__('The {0} has been saved.', 'Business Unit'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Business Unit'));
            }
        }
        $this->set(compact('businessUnit'));
        $this->set('_serialize', ['businessUnit']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Business Unit id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     * @throws \Cake\Datasource\Exception\
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $businessUnit = $this->BusinessUnits->get($id);
        try {
            if ($this->BusinessUnits->delete($businessUnit)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Business Unit'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Business Unit'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['action' => 'index']);
    }}
