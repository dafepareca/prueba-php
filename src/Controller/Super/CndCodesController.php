<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * CndCodes Controller
 *
 * @property \App\Model\Table\CndCodesTable $CndCodes
 *
 * @method \App\Model\Entity\CndCode[] paginate($object = null, array $settings = [])
 */
class CndCodesController extends AppController
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
            'order' => ['CndCodes.id' => 'ASC']
        ];
        $cndCodes = $this->paginate($this->CndCodes);
        $this->set(compact('cndCodes'));
        $this->set('_serialize', ['cndCodes']);
    }

    /**
     * View method
     *
     * @param string|null $id Cnd Code id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cndCode = $this->CndCodes->get($id, [
            'contain' => []
        ]);

        $this->set('cndCode', $cndCode);
        $this->set('_serialize', ['cndCode']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $cndCode = $this->CndCodes->newEntity();
        if ($this->request->is('post')) {
            $cndCode = $this->CndCodes->patchEntity($cndCode, $this->request->getData());
            if ($this->CndCodes->save($cndCode)) {
                $this->Flash->success(__('The {0} has been saved.', 'Cnd Code'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Cnd Code'));
            }
        }
        $this->set(compact('cndCode'));
        $this->set('_serialize', ['cndCode']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Cnd Code id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cndCode = $this->CndCodes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cndCode = $this->CndCodes->patchEntity($cndCode, $this->request->getData());
            if ($this->CndCodes->save($cndCode)) {
                $this->Flash->success(__('The {0} has been saved.', 'Cnd Code'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Cnd Code'));
            }
        }
        $this->set(compact('cndCode'));
        $this->set('_serialize', ['cndCode']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Cnd Code id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cndCode = $this->CndCodes->get($id);
        if ($this->CndCodes->delete($cndCode)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Cnd Code'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Cnd Code'));
        }
        return $this->redirect(['action' => 'index']);
    }}
