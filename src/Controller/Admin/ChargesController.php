<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\Log;
use Cake\Cache\Cache;

/**
 * Charges Controller
 *
 * @property \App\Model\Table\ChargesTable $Charges
 *
 * @method \App\Model\Entity\Charge[] paginate($object = null, array $settings = [])
 */
class ChargesController extends AppController
{

    /**
    * Index method
    * @return \Cake\Network\Response|null
    */
    public function index()
    {
        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();
        $conditions['type_charge'] = 2;
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [],
            'order' => ['Charges.id' => 'DESC']
        ];
        $charges = $this->paginate($this->Charges);
        $this->set(compact('charges'));
        $this->set('_serialize', ['charges']);
    }

    /**
     * View method
     *
     * @param string|null $id Charge id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {

        $q = $this->Rejected->find();

        $q->select([
            'count' => $q->func()->count('*'),
            'id_type_rejected'
        ])->group('id_type_rejected');

        $charge = $this->Charges->get($id, [
            'contain' => ['Customers', 'Obligations']
        ]);

        $this->set('charge', $charge);
        $this->set('_serialize', ['charge']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $settings = Cache::read('settings', 'long');
        $charge = $this->Charges->newEntity();
        try {
            if ($this->request->is('post')) {
                $this->request->data['name_file'] = $this->request->getData('file.name');
                $this->request->data['state'] = 2;
                $this->request->data['type_charge'] = 2;
                $charge = $this->Charges->patchEntity($charge, $this->request->getData());
                $peso = $this->request->getData('file.size');
                if($peso < 400000000){
                    if ($this->Charges->save($charge)) {
                        $this->Charges->updateAll(['state' => 3],['state'=>2,'id <>'=>$charge->id,'type_charge' => 2]);
                        
                        if ((int)$settings['FileRepositoryLocal']['activate'] && (int)$settings['FileRepositoryLocal']['activate'] > 0) {
                            $this->Charges->updateAll(['file_dir' => $settings['FileRepositoryLocal']['path']], ['id' => $charge->id]);
                        }
                        
                        $this->Flash->success(__('The {0} has been saved.', 'Charge'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Charge'));
                        return $this->redirect(['action' => 'index']);
                    }
                }else{
                    $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Charge'));
                    \Cake\Log\Log::error("Sobre pasa el peso del archivo.");
                    return $this->redirect(['action' => 'index']);
                }
            }
        } catch (\Throwable $th) {
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Charge'));
            \Cake\Log\Log::error($th);
            return $this->redirect(['action' => 'index']);
        }
        
        $this->set(compact('charge'));
        $this->set('_serialize', ['charge']);
    }
    /**
     * Edit method
     *
     * @param string|null $id Charge id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $charge = $this->Charges->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $charge = $this->Charges->patchEntity($charge, $this->request->getData());
            if ($this->Charges->save($charge)) {
                $this->Flash->success(__('The {0} has been saved.', 'Charge'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Charge'));
            }
        }
        $this->set(compact('charge'));
        $this->set('_serialize', ['charge']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Charge id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $charge = $this->Charges->get($id);
        if ($this->Charges->delete($charge)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Charge'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Charge'));
        }
        return $this->redirect(['action' => 'index']);
    }}
