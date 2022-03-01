<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;

/**
 * SettingCategories Controller
 *
 * @property \App\Model\Table\SettingCategoriesTable $SettingCategories
 * @property \App\Controller\Component\SearchComponent $Search
 */
class SettingCategoriesController extends AppController
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
            'order' => ['SettingCategories.id' => 'ASC']
        ];
        $settingCategories = $this->paginate($this->SettingCategories);
        $this->set(compact('settingCategories'));
        $this->set('_serialize', ['settingCategories']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $settingCategory = $this->SettingCategories->newEntity();
        if ($this->request->is('post')) {
            $settingCategory = $this->SettingCategories->patchEntity($settingCategory, $this->request->getData());
            if ($result = $this->SettingCategories->save($settingCategory)) {
                $this->Flash->success(__('The {0} has been saved.', 'Setting Category'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Setting Category'));
            }
        }
        $this->set(compact('settingCategory'));
        $this->set('_serialize', ['settingCategory']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Setting Category id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $settingCategory = $this->SettingCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $settingCategory = $this->SettingCategories->patchEntity($settingCategory, $this->request->getData());
            if ($this->SettingCategories->save($settingCategory)) {
                $this->Flash->success(__('The {0} has been saved.', 'Setting Category'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Setting Category'));
            }
        }
        $this->set(compact('settingCategory'));
        $this->set('_serialize', ['settingCategory']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting Category id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $settingCategory = $this->SettingCategories->get($id);
        try {
            if ($this->SettingCategories->delete($settingCategory)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Setting Category'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Setting Category'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['action' => 'index']);
    }}
