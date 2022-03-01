<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * ProductCodes Controller
 *
 * @property \App\Model\Table\ProductCodesTable $ProductCodes
 *
 * @method \App\Model\Entity\ProductCode[] paginate($object = null, array $settings = [])
 */
class ProductCodesController extends AppController
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
            'order' => ['ProductCodes.id' => 'ASC']
        ];
        $productCodes = $this->paginate($this->ProductCodes);
        $this->set(compact('productCodes'));
        $this->set('_serialize', ['productCodes']);
    }

    /**
     * View method
     *
     * @param string|null $id Product Code id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productCode = $this->ProductCodes->get($id, [
            'contain' => []
        ]);

        $this->set('productCode', $productCode);
        $this->set('_serialize', ['productCode']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productCode = $this->ProductCodes->newEntity();
        if ($this->request->is('post')) {
            $productCode = $this->ProductCodes->patchEntity($productCode, $this->request->getData());
            if ($this->ProductCodes->save($productCode)) {
                $this->Flash->success(__('The {0} has been saved.', 'Product Code'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Product Code'));
            }
        }
        $this->set(compact('productCode'));
        $this->set('_serialize', ['productCode']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Code id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productCode = $this->ProductCodes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productCode = $this->ProductCodes->patchEntity($productCode, $this->request->getData());
            if ($this->ProductCodes->save($productCode)) {
                $this->Flash->success(__('The {0} has been saved.', 'Product Code'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Product Code'));
            }
        }
        $this->set(compact('productCode'));
        $this->set('_serialize', ['productCode']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Code id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productCode = $this->ProductCodes->get($id);
        if ($this->ProductCodes->delete($productCode)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Product Code'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Product Code'));
        }
        return $this->redirect(['action' => 'index']);
    }}
