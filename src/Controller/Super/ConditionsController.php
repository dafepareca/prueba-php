<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use App\Model\Entity\Log;
use App\Model\Table\TypesConditionsTable;
use Cake\Event\Event;
use Cake\Cache\Cache;

/**
 * Conditions Controller
 *
 * @property \App\Model\Table\ConditionsTable $Conditions
 * @property \App\Controller\Component\SearchComponent $Search
 *
 * @method \App\Model\Entity\Condition[] paginate($object = null, array $settings = [])
 */
class ConditionsController extends AppController
{

    public $type_condition_id = null;
    public $condition = '';

    public function beforeFilter(Event $event){
        parent::beforeFilter($event);

        $this->Security->setConfig(
            'unlockedActions', ['sort']
        );

        $type_condition_id = $this->request->getQuery('type_condition_id');
        $this->type_condition_id = $type_condition_id;
        $condition_id = $this->request->getQuery('condition_id');
        if(isset($condition_id) and !empty($condition_id)){
            $this->condition = $this->Conditions->get($condition_id,['contain'=>['TypesConditions']]);
        }

        if(isset($type_condition_id) and !empty($type_condition_id)){
            $this->type_condition_id = $type_condition_id;
            $typeCondition = $this->Conditions->TypesConditions->get($this->type_condition_id, [
                'fields' => [
                    'TypesConditions.type',
                    'TypesConditions.id',
                    'TypesConditions.label_value',
                    'TypesConditions.label_compare',
                    'TypesConditions.options'
                ],
            ]);

            if($type_condition_id == TypesConditionsTable::PORCENTAJEDISMINUCION){
                $opciones = [];
                $condiciones = Cache::read('conditions','long');
                $condicionesTA = $condiciones[TypesConditionsTable::PORCENTAJECONDONACION];
                foreach ($condicionesTA as $condicion){
                    $opciones[$condicion->value] = $condicion->value;
                }
                $this->set('opciones', $opciones);
            }

            $opcionesValor = [];
            if(!empty($typeCondition->options)){
                $items = explode(',',$typeCondition->options);
                foreach ($items as $item){
                    $opcionesValor[$item]=$item;
                }
            }

            $label1 = $typeCondition->label_compare;
            $label2 = $typeCondition->label_value;

            $this->set('condition', $this->condition);
            $this->set('label1', $label1);
            $this->set('label2', $label2);
            $this->set('typeCondition', $typeCondition);
            $this->set('type_condition_id', $this->type_condition_id);
            $this->set('opcionesValor', $opcionesValor);

        }else{
            $this->Flash->error(__('No condition selected. Please, try again.'));
            return $this->redirect(array_merge(['controller' => 'users', 'action' => 'index']));
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
            'contain' => ['TypesConditions'],
            'order' => ['Conditions.sort' => 'ASC']
        ];
        $conditions = $this->paginate($this->Conditions);
        $this->set(compact('conditions'));
        $this->set('_serialize', ['conditions']);
        if($this->type_condition_id == TypesConditionsTable::PORCENTAJECONDONACION){
            $this->render('index_hp');
        }
    }

    /**
     * View method
     *
     * @param string|null $id Condition id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $condition = $this->Conditions->get($id, [
            'contain' => ['TypesConditions']
        ]);

        $this->set('condition', $condition);
        $this->set('_serialize', ['condition']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $condition = $this->Conditions->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->getData();
            if(isset($data['value_2'])){
                $data['value'] = $data['value'].'-'.$data['value_2'];
            }
            $condition = $this->Conditions->patchEntity($condition, $data);
            $condition->type_condition_id = $this->type_condition_id;
            $condition->condition_id = (!empty($this->condition))?$this->condition->id:null;
            if ($this->Conditions->save($condition)) {
                Cache::delete('conditions', 'long');
                $this->Flash->success(__('The {0} has been saved.', 'Condition'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Condition'));
            }
        }
        $typesConditions = $this->Conditions->TypesConditions->find('list', ['limit' => 200]);
        $this->set(compact('condition', 'typesConditions'));
        $this->set('_serialize', ['condition']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Condition id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $condition = $this->Conditions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

           $data = $this->request->getData();
           if(isset($data['value_2'])){
            $data['value'] = $data['value'].'-'.$data['value_2'];
           }

            $condition = $this->Conditions->patchEntity($condition, $data);
            if ($this->Conditions->save($condition)) {
                Cache::delete('conditions', 'long');
                $this->Flash->success(__('The {0} has been saved.', 'Condition'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Condition'));
            }
        }
        $typesConditions = $this->Conditions->TypesConditions->find('list', ['limit' => 200]);

        if($this->type_condition_id == TypesConditionsTable::PORCENTAJECONDONACION){
            $valores = explode('-',$condition->value);
            $condition->value = $valores[0];
            $condition->value_2 = $valores[1];
        }

        $this->set(compact('condition', 'typesConditions'));
        $this->set('_serialize', ['condition']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Condition id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $condition = $this->Conditions->get($id);
        if ($this->Conditions->delete($condition)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Condition'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Condition'));
        }
        return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
    }

    /**
     * Sort method
     * @return \Cake\Network\Response|null
     */
    public function sort()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $conditions = $this->request->getData('condition');
            if(count($conditions) > 0){
                foreach ($conditions as $key => $value) {
                    $query = $this->Conditions->query();
                    $query->update()
                        ->set(['sort' => $key + 1])
                        ->where(['id' => $value])
                        ->execute();
                }
            }
            $this->Flash->success(__('{0} have been sorted.', __('Condition')));
            return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
        }

        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();

        $conditions = $this->Conditions->find('all')
            ->where($conditions)
            ->order(['Conditions.sort' => 'ASC', 'Conditions.id' => 'ASC']);
        $this->set(compact('conditions'));
        $this->set('_serialize', ['conditions']);
    }

}
