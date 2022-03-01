<?php
namespace App\Controller\Asesor;

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

        $this->viewBuilder()->setTemplatePath('/Trainings');
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

        $this->viewBuilder()->setTemplatePath('/Trainings');
    }

    /**
     * View method
     *
     * @param string|null $id Training id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function viewResorce($id = null)
    {
        $resource = $this->Trainings->TrainingResources->get($id, [

        ]);

        $this->set('resource', $resource);
        $this->set('_serialize', ['resource']);

        $this->viewBuilder()->setTemplatePath('/Trainings');
    }

}
