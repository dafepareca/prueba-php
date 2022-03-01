<?php
namespace App\Controller\Super;

use App\Controller\AppController;

/**
 * LegalCodes Controller
 *
 * @property \App\Model\Table\LegalCodesTable $LegalCodes
 *
 * @method \App\Model\Entity\LegalCode[] paginate($object = null, array $settings = [])
 */
class LegalCodesController extends AppController
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
            'order' => ['LegalCodes.id' => 'ASC']
        ];
        $legalCodes = $this->paginate($this->LegalCodes);
        $this->set(compact('legalCodes'));
        $this->set('_serialize', ['legalCodes']);
    }

    /**
     * View method
     *
     * @param string|null $id Legal Code id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $legalCode = $this->LegalCodes->get($id, [
            'contain' => []
        ]);

        $this->set('legalCode', $legalCode);
        $this->set('_serialize', ['legalCode']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $legalCode = $this->LegalCodes->newEntity();
        if ($this->request->is('post')) {
            $legalCode = $this->LegalCodes->patchEntity($legalCode, $this->request->getData());
            if ($this->LegalCodes->save($legalCode)) {
                $this->Flash->success(__('The {0} has been saved.', 'Legal Code'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Legal Code'));
            }
        }
        $this->set(compact('legalCode'));
        $this->set('_serialize', ['legalCode']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Legal Code id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $legalCode = $this->LegalCodes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $legalCode = $this->LegalCodes->patchEntity($legalCode, $this->request->getData());
            if ($this->LegalCodes->save($legalCode)) {
                $this->Flash->success(__('The {0} has been saved.', 'Legal Code'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Legal Code'));
            }
        }
        $this->set(compact('legalCode'));
        $this->set('_serialize', ['legalCode']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Legal Code id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $legalCode = $this->LegalCodes->get($id);
        if ($this->LegalCodes->delete($legalCode)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Legal Code'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Legal Code'));
        }
        return $this->redirect(['action' => 'index']);
    }}
