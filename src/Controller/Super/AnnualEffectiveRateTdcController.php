<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * AnnualEffectiveRateTdc Controller
 *
 * @property \App\Model\Table\AnnualEffectiveRateTdcTable $AnnualEffectiveRateTdc
 *
 * @method \App\Model\Entity\AnnualEffectiveRateTdc[] paginate($object = null, array $settings = [])
 */
class AnnualEffectiveRateTdcController extends AppController
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
            'order' => ['AnnualEffectiveRateTdc.id' => 'DESC']
        ];
        $annualEffectiveRateTdc = $this->paginate($this->AnnualEffectiveRateTdc);
        $this->set(compact('annualEffectiveRateTdc'));
        $this->set('_serialize', ['annualEffectiveRateTdc']);
    }

    /**
     * View method
     *
     * @param string|null $id Annual Effective Rate Tdc id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $annualEffectiveRateTdc = $this->AnnualEffectiveRateTdc->get($id, [
            'contain' => []
        ]);

        $this->set('annualEffectiveRateTdc', $annualEffectiveRateTdc);
        $this->set('_serialize', ['annualEffectiveRateTdc']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $annualEffectiveRateTdc = $this->AnnualEffectiveRateTdc->newEntity();
        if ($this->request->is('post')) {
            $annualEffectiveRateTdc = $this->AnnualEffectiveRateTdc->patchEntity($annualEffectiveRateTdc, $this->request->getData());
            $annualEffectiveRateTdc->fecha = $annualEffectiveRateTdc->fecha.'-01';
            if ($this->AnnualEffectiveRateTdc->save($annualEffectiveRateTdc)) {
                $this->Flash->success(__('The {0} has been saved.', 'Annual Effective Rate Tdc'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Annual Effective Rate Tdc'));
            }
        }
        $this->set(compact('annualEffectiveRateTdc'));
        $this->set('_serialize', ['annualEffectiveRateTdc']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Annual Effective Rate Tdc id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $annualEffectiveRateTdc = $this->AnnualEffectiveRateTdc->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $annualEffectiveRateTdc = $this->AnnualEffectiveRateTdc->patchEntity($annualEffectiveRateTdc, $this->request->getData());
            $annualEffectiveRateTdc->fecha = $annualEffectiveRateTdc->fecha.'-01';
            if ($this->AnnualEffectiveRateTdc->save($annualEffectiveRateTdc)) {
                $this->Flash->success(__('The {0} has been saved.', 'Annual Effective Rate Tdc'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Annual Effective Rate Tdc'));
            }
        }
        $this->set(compact('annualEffectiveRateTdc'));
        $this->set('_serialize', ['annualEffectiveRateTdc']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Annual Effective Rate Tdc id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete','put']);
        $annualEffectiveRateTdc = $this->AnnualEffectiveRateTdc->get($id);
        if ($this->AnnualEffectiveRateTdc->delete($annualEffectiveRateTdc)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Annual Effective Rate Tdc'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Annual Effective Rate Tdc'));
        }
        return $this->redirect(['action' => 'index']);
    }}
