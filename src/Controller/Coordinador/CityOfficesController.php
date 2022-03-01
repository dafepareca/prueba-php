<?php
namespace App\Controller\Coordinador;

use App\Controller\AppController;

/**
 * CityOffices Controller
 *
 * @property \App\Model\Table\CityOfficesTable $CityOffices
 * @property \App\Model\Table\OfficesTable $Offices
 *
 * @method \App\Model\Entity\CityOffice[] paginate($object = null, array $settings = [])
 */
class CityOfficesController extends AppController
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
            'order' => ['CityOffices.id' => 'ASC']
        ];
        $cityOffices = $this->paginate($this->CityOffices);
        $this->set(compact('cityOffices'));
        $this->set('_serialize', ['cityOffices']);
    }

    /**
     * View method
     *
     * @param string|null $id City Office id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cityOffice = $this->CityOffices->get($id, [
            'contain' => ['Offices']
        ]);

        $this->set('cityOffice', $cityOffice);
        $this->set('_serialize', ['cityOffice']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $cityOffice = $this->CityOffices->newEntity();
        if ($this->request->is('post')) {
            $cityOffice = $this->CityOffices->patchEntity($cityOffice, $this->request->getData());
            if ($this->CityOffices->save($cityOffice)) {
                $this->Flash->success(__('The {0} has been saved.', 'City Office'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'City Office'));
            }
        }
        $this->set(compact('cityOffice'));
        $this->set('_serialize', ['cityOffice']);
    }

    /**
     * Edit method
     *
     * @param string|null $id City Office id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cityOffice = $this->CityOffices->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cityOffice = $this->CityOffices->patchEntity($cityOffice, $this->request->getData());
            if ($this->CityOffices->save($cityOffice)) {
                $this->Flash->success(__('The {0} has been saved.', 'City Office'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'City Office'));
            }
        }
        $this->set(compact('cityOffice'));
        $this->set('_serialize', ['cityOffice']);
    }

    /**
     * Delete method
     *
     * @param string|null $id City Office id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cityOffice = $this->CityOffices->get($id);
        if ($this->CityOffices->delete($cityOffice)) {
            $this->Flash->success(__('The {0} has been deleted.', 'City Office'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'City Office'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function oficinas_ciudad($id){

        $this->loadModel('Offices');

        $oficinas = $this->Offices->find()
            ->contain(['Schedules'])
            ->where(['city_office_id' => $id])
            ->order('name ASC')
            ->all();

        $this->set(['oficinas' => $oficinas]);

    }
}
