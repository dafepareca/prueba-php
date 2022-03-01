<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * AnnualEffectiveRateUvr Controller
 *
 * @property \App\Model\Table\AnnualEffectiveRateUvrTable $AnnualEffectiveRateUvr
 *
 * @method \App\Model\Entity\AnnualEffectiveRateUvr[] paginate($object = null, array $settings = [])
 */
class AnnualEffectiveRateUvrController extends AppController
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
            'order' => ['AnnualEffectiveRateUvr.id' => 'DESC']
        ];
        $annualEffectiveRateUvr = $this->paginate($this->AnnualEffectiveRateUvr);
        $this->set(compact('annualEffectiveRateUvr'));
        $this->set('_serialize', ['annualEffectiveRateUvr']);
    }

    /**
     * View method
     *
     * @param string|null $id UVR Rate id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $annualEffectiveRateUvr = $this->AnnualEffectiveRateUvr->get($id, [
            'contain' => []
        ]);

        $this->set('annualEffectiveRateUvr', $annualEffectiveRateUvr);
        $this->set('_serialize', ['annualEffectiveRateUvr']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $annualEffectiveRateUvr = $this->AnnualEffectiveRateUvr->newEntity();
        if ($this->request->is('post')) {
            $annualEffectiveRateUvr = $this->AnnualEffectiveRateUvr->patchEntity($annualEffectiveRateUvr, $this->request->getData());
            $annualEffectiveRateUvr->month_date = $annualEffectiveRateUvr->month_date.'-01';
            if ($this->AnnualEffectiveRateUvr->save($annualEffectiveRateUvr)) {
                $this->Flash->success(__('The {0} has been saved.', 'UVR Rate'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'UVR Rate'));
            }
        }
        $this->set(compact('annualEffectiveRateUvr'));
        $this->set('_serialize', ['annualEffectiveRateUvr']);
    }

    /**
     * Edit method
     *
     * @param string|null $id UVR Rate id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $annualEffectiveRateUvr = $this->AnnualEffectiveRateUvr->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $annualEffectiveRateUvr = $this->AnnualEffectiveRateUvr->patchEntity($annualEffectiveRateUvr, $this->request->getData());
            $annualEffectiveRateUvr->month_date = $annualEffectiveRateUvr->month_date.'-01';
            if ($this->AnnualEffectiveRateUvr->save($annualEffectiveRateUvr)) {
                $this->Flash->success(__('The {0} has been saved.', 'UVR Rate'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'UVR Rate'));
            }
        }
        $this->set(compact('annualEffectiveRateUvr'));
        $this->set('_serialize', ['annualEffectiveRateUvr']);
    }

    /**
     * Delete method
     *
     * @param string|null $id UVR Rate id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete','put']);
        $annualEffectiveRateUvr = $this->AnnualEffectiveRateUvr->get($id);
        if ($this->AnnualEffectiveRateUvr->delete($annualEffectiveRateUvr)) {
            $this->Flash->success(__('The {0} has been deleted.', 'UVR Rate'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'UVR Rate'));
        }
        return $this->redirect(['action' => 'index']);
    }}
