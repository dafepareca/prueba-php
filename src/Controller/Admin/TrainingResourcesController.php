<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * TrainingResources Controller
 *
 * @property \App\Model\Table\TrainingResourcesTable $TrainingResources
 *
 * @method \App\Model\Entity\TrainingResource[] paginate($object = null, array $settings = [])
 */
class TrainingResourcesController extends AppController
{

    public $training = null;
    public function beforeFilter(Event $event){
        if(!($this->request->is('delete'))){
            parent::beforeFilter($event);
            $training_id = $this->request->getQuery('training_id');
            if(isset($training_id) and !empty($training_id)){
                $this->training = $this->TrainingResources->Trainings->get($training_id, [
    
                ]);
                $this->set('training', $this->training);
            }else{
                $this->Flash->error(__('No Access Training selected. Please, try again.'));
                return $this->redirect(array_merge(['controller' => 'trainings', 'action' => 'index']));
            }
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
            'contain' => ['Trainings'],
            'order' => ['TrainingResources.id' => 'ASC']
        ];
        $trainingResources = $this->paginate($this->TrainingResources);
        $this->set(compact('trainingResources'));
        $this->set('_serialize', ['trainingResources']);
    }

    /**
     * View method
     *
     * @param string|null $id Training Resource id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $trainingResource = $this->TrainingResources->get($id, [
            'contain' => ['Trainings']
        ]);

        $this->set('trainingResource', $trainingResource);
        $this->set('_serialize', ['trainingResource']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $trainingResource = $this->TrainingResources->newEntity();
        if ($this->request->is('post')) {
            $trainingResource = $this->TrainingResources->patchEntity($trainingResource, $this->request->getData());
            $trainingResource->training_id = $this->training->id;
            if ($this->TrainingResources->save($trainingResource)) {
                $this->Flash->success(__('The {0} has been saved.', 'Training Resource'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                pr($trainingResource->getErrors());
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Training Resource'));
            }
        }
        $trainings = $this->TrainingResources->Trainings->find('list', ['limit' => 200]);
        $this->set(compact('trainingResource', 'trainings'));
        $this->set('_serialize', ['trainingResource']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Training Resource id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $trainingResource = $this->TrainingResources->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $trainingResource = $this->TrainingResources->patchEntity($trainingResource, $this->request->getData());
            if ($this->TrainingResources->save($trainingResource)) {
                $this->Flash->success(__('The {0} has been saved.', 'Training Resource'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Training Resource'));
            }
        }
        $trainings = $this->TrainingResources->Trainings->find('list', ['limit' => 200]);
        $this->set(compact('trainingResource', 'trainings'));
        $this->set('_serialize', ['trainingResource']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Training Resource id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $trainingResource = $this->TrainingResources->get($id);
        if ($this->TrainingResources->delete($trainingResource)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Training Resource'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Training Resource'));
        }
        return $this->redirect(array_merge(['controller' => 'trainings', 'action' => 'index']));
        //return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
    }}
