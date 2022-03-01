<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Schedules Controller
 *
 * @property \App\Model\Table\SchedulesTable $Schedules
 *
 * @method \App\Model\Entity\Schedule[] paginate($object = null, array $settings = [])
 */
class SchedulesController extends AppController
{


    public $office = null;
    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->loadModel('Offices');
        $office_id = $this->request->getQuery('office_id');
        if(isset($office_id) and !empty($office_id)){
            $this->office = $this->Offices->get($office_id, [
                'fields' => [
                    'Offices.id',
                    'Offices.name',
                    'Offices.city_office_id'
                ],
            ]);
            $this->set('office', $this->office);
        }else{
            $this->Flash->error(__('No office selected. Please, try again.'));
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
            'contain' => ['Offices'],
            'order' => ['Schedules.id' => 'ASC']
        ];
        $schedules = $this->paginate($this->Schedules);
        $this->set(compact('schedules'));
        $this->set('_serialize', ['schedules']);
    }

    /**
     * View method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $schedule = $this->Schedules->get($id, [
            'contain' => ['Offices']
        ]);

        $this->set('schedule', $schedule);
        $this->set('_serialize', ['schedule']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $schedule = $this->Schedules->newEntity();
        if ($this->request->is('post')) {
            $schedule = $this->Schedules->patchEntity($schedule, $this->request->getData());
            $schedule->office_id = $this->office->id;
            if ($this->Schedules->save($schedule)) {
                $this->Flash->success(__('The {0} has been saved.', 'Schedule'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Schedule'));
            }
        }
        $offices = $this->Schedules->Offices->find('list', ['limit' => 200]);
        $this->set(compact('schedule', 'offices'));
        $this->set('_serialize', ['schedule']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $schedule = $this->Schedules->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $schedule = $this->Schedules->patchEntity($schedule, $this->request->getData());
            if ($this->Schedules->save($schedule)) {
                $this->Flash->success(__('The {0} has been saved.', 'Schedule'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Schedule'));
            }
        }
        $offices = $this->Schedules->Offices->find('list', ['limit' => 200]);
        $this->set(compact('schedule', 'offices'));
        $this->set('_serialize', ['schedule']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Schedule id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $schedule = $this->Schedules->get($id);
        if ($this->Schedules->delete($schedule)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Schedule'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Schedule'));
        }
        return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
    }}
