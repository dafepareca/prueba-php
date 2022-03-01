<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * ModalMessage Controller
 *
 * @property \App\Model\Table\ModalMessageTable $ModalMessage
 *
 * @method \App\Model\Entity\ModalMessage[] paginate($object = null, array $settings = [])
 */
class ModalMessageController extends AppController
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
            'order' => ['ModalMessage.id' => 'ASC']
        ];
        $modalMessage = $this->paginate($this->ModalMessage);
        $this->set(compact('modalMessage'));
        $this->set('_serialize', ['modalMessage']);
    }

    /**
     * View method
     *
     * @param string|null $id Modal Message id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $modalMessage = $this->ModalMessage->get($id, [
            'contain' => []
        ]);

        $this->set('modalMessage', $modalMessage);
        $this->set('_serialize', ['modalMessage']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $modalMessage = $this->ModalMessage->newEntity();
        if ($this->request->is('post')) {
            $modalMessage = $this->ModalMessage->patchEntity($modalMessage, $this->request->getData());
            if ($this->ModalMessage->save($modalMessage)) {
                $this->Flash->success(__('The {0} has been saved.', 'Modal Message'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Modal Message'));
            }
        }
        $this->set(compact('modalMessage'));
        $this->set('_serialize', ['modalMessage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Modal Message id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $modalMessage = $this->ModalMessage->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $modalMessage = $this->ModalMessage->patchEntity($modalMessage, $this->request->getData());
            if ($this->ModalMessage->save($modalMessage)) {
                $this->Flash->success(__('The {0} has been saved.', 'Modal Message'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Modal Message'));
            }
        }
        $this->set(compact('modalMessage'));
        $this->set('_serialize', ['modalMessage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Modal Message id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $modalMessage = $this->ModalMessage->get($id);
        if ($this->ModalMessage->delete($modalMessage)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Modal Message'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Modal Message'));
        }
        return $this->redirect(['action' => 'index']);
    }}
