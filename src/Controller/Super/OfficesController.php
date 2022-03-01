<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Offices Controller
 *
 * @property \App\Model\Table\OfficesTable $Offices
 *
 * @method \App\Model\Entity\Office[] paginate($object = null, array $settings = [])
 */
class OfficesController extends AppController
{


    public $city = null;
    public function beforeFilter(Event $event){

        parent::beforeFilter($event);
        $this->loadModel('CityOffices');
        $city_office_id = $this->request->getQuery('city_office_id');
        if(isset($city_office_id) and !empty($city_office_id)){
            $this->city = $this->CityOffices->get($city_office_id, [
                'fields' => [
                    'CityOffices.id',
                    'CityOffices.name'
                ],
            ]);
            $this->set('city', $this->city);
        }else{
            $this->Flash->error(__('No city selected. Please, try again.'));
            return $this->redirect(array_merge(['controller' => 'cityOffices', 'action' => 'index']));
        }
    }

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
            'contain' => ['CityOffices'],
            'order' => ['Offices.id' => 'ASC']
        ];
        $offices = $this->paginate($this->Offices);
        $this->set(compact('offices'));
        $this->set('_serialize', ['offices']);
    }

    /**
     * View method
     *
     * @param string|null $id Office id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $office = $this->Offices->get($id, [
            'contain' => ['CityOffices', 'Schedules']
        ]);

        $this->set('office', $office);
        $this->set('_serialize', ['office']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $office = $this->Offices->newEntity();
        if ($this->request->is('post')) {
            $office = $this->Offices->patchEntity($office, $this->request->getData());
            $office->city_office_id = $this->city->id;
            if ($this->Offices->save($office)) {
                $this->Flash->success(__('The {0} has been saved.', 'Office'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Office'));
            }
        }
        $cityOffices = $this->Offices->CityOffices->find('list', ['limit' => 200]);
        $this->set(compact('office', 'cityOffices'));
        $this->set('_serialize', ['office']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Office id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $office = $this->Offices->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $office = $this->Offices->patchEntity($office, $this->request->getData());
            if ($this->Offices->save($office)) {
                $this->Flash->success(__('The {0} has been saved.', 'Office'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Office'));
            }
        }
        $cityOffices = $this->Offices->CityOffices->find('list', ['limit' => 200]);
        $this->set(compact('office', 'cityOffices'));
        $this->set('_serialize', ['office']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Office id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $office = $this->Offices->get($id);
        if ($this->Offices->delete($office)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Office'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Office'));
        }
        return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
    }}
