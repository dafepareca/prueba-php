<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Trainings Controller
 *
 * @property \App\Model\Table\TrainingsTable $Trainings
 *
 * @method \App\Model\Entity\Training[] paginate($object = null, array $settings = [])
 */
class TrainingsController extends AppController
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
            'order' => ['Trainings.id' => 'ASC']
        ];
        $trainings = $this->paginate($this->Trainings);
        $this->set(compact('trainings'));
        $this->set('_serialize', ['trainings']);
    }

    /**
     * View method
     *
     * @param string|null $id Training id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $training = $this->Trainings->get($id, [
            'contain' => ['TrainingResources']
        ]);

        $this->set('training', $training);
        $this->set('_serialize', ['training']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $training = $this->Trainings->newEntity();
        if ($this->request->is('post')) {
            $training = $this->Trainings->patchEntity($training, $this->request->getData());
            if ($this->Trainings->save($training)) {
                $this->Flash->success(__('The {0} has been saved.', 'Training'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Training'));
            }
        }
        $this->set(compact('training'));
        $this->set('_serialize', ['training']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Training id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $training = $this->Trainings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $training = $this->Trainings->patchEntity($training, $this->request->getData());
            if ($this->Trainings->save($training)) {
                $this->Flash->success(__('The {0} has been saved.', 'Training'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Training'));
            }
        }
        $this->set(compact('training'));
        $this->set('_serialize', ['training']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Training id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $training = $this->Trainings->get($id);
        if ($this->Trainings->delete($training)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Training'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Training'));
        }
        return $this->redirect(['action' => 'index']);
    }}
