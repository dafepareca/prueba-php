<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * ValueStages Controller
 *
 * @property \App\Model\Table\ValueStagesTable $ValueStages
 * @property \App\Model\Table\LegalCodesTable $LegalCodes
 *
 * @method \App\Model\Entity\ValueStage[] paginate($object = null, array $settings = [])
 */
class ValueStagesController extends AppController
{


    public $estapa = null;
    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $this->loadModel('LegalCodes');
        $legal_codes_id = $this->request->getQuery('legal_codes_id');
        if(isset($legal_codes_id) and !empty($legal_codes_id)){
            $this->estapa = $this->LegalCodes->get($legal_codes_id, [
                'fields' => [
                    'LegalCodes.id',
                    'LegalCodes.description'
                ],
            ]);
            $this->set('etapa', $this->estapa);
        }else{
            $this->Flash->error(__('No estapa selected. Please, try again.'));
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
            'contain' => ['LegalCodes', 'CityOffices'],
            'order' => ['ValueStages.id' => 'ASC']
        ];
        $valueStages = $this->paginate($this->ValueStages);
        $this->set(compact('valueStages'));
        $this->set('_serialize', ['valueStages']);
    }

    /**
     * View method
     *
     * @param string|null $id Value Stage id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $valueStage = $this->ValueStages->get($id, [
            'contain' => ['LegalCodes', 'CityOffices']
        ]);

        $this->set('valueStage', $valueStage);
        $this->set('_serialize', ['valueStage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $valueStage = $this->ValueStages->newEntity();
        if ($this->request->is('post')) {
            $valueStage = $this->ValueStages->patchEntity($valueStage, $this->request->getData());
            $valueStage->legal_codes_id = $this->estapa->id;
            if ($this->ValueStages->save($valueStage)) {
                $this->Flash->success(__('The {0} has been saved.', 'Value Stage'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Value Stage'));
            }
        }
        $legalCodes = $this->ValueStages->LegalCodes->find('list', ['limit' => 200]);
        $cityOffices = $this->ValueStages->CityOffices->find('list', ['limit' => 200]);
        $this->set(compact('valueStage', 'legalCodes', 'cityOffices'));
        $this->set('_serialize', ['valueStage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Value Stage id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $valueStage = $this->ValueStages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $valueStage = $this->ValueStages->patchEntity($valueStage, $this->request->getData());
            if ($this->ValueStages->save($valueStage)) {
                $this->Flash->success(__('The {0} has been saved.', 'Value Stage'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Value Stage'));
            }
        }
        $legalCodes = $this->ValueStages->LegalCodes->find('list', ['limit' => 200]);
        $cityOffices = $this->ValueStages->CityOffices->find('list', ['limit' => 200]);
        $this->set(compact('valueStage', 'legalCodes', 'cityOffices'));
        $this->set('_serialize', ['valueStage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Value Stage id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $valueStage = $this->ValueStages->get($id);
        if ($this->ValueStages->delete($valueStage)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Value Stage'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Value Stage'));
        }
        return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
    }}
