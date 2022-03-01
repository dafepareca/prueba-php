<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\Core\Exception\Exception;

/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 * @property \App\Controller\Component\SearchComponent $Search
 * @property \Cake\Controller\Component\PaginatorComponent $paginate
 */
class SettingsController extends AppController
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
            'contain' => ['SettingCategories'],
            'order' => [
                'SettingCategories.name' => 'ASC',
                'Settings.name' => 'ASC',
            ]
        ];
        $settings = $this->paginate($this->Settings);
        $settingCategories = $this->Settings->SettingCategories->find('list', ['limit' => 200]);
        $this->set(compact('settings', 'settingCategories'));
        $this->set('_serialize', ['settings']);
    }

    /**
     * View method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => ['SettingCategories']
        ]);

        $this->set('setting', $setting);
        $this->set('_serialize', ['setting']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $setting = $this->Settings->newEntity();
        if ($this->request->is('post')) {
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            if ($result = $this->Settings->save($setting)) {
                Cache::delete('settings', 'long');
                $this->Flash->success(__('The {0} has been saved.', 'Setting'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Setting'));
            }
        }
        $settingCategories = $this->Settings->SettingCategories->find('list', ['limit' => 200]);
        $types = $this->Settings->getListTypes();
        $this->set(compact('setting', 'settingCategories', 'types'));
        $this->set('_serialize', ['setting']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $setting = $this->Settings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $setting = $this->Settings->patchEntity($setting, $this->request->getData());
            if ($this->Settings->save($setting)) {
                Cache::delete('settings', 'long');
                $this->Flash->success(__('The {0} has been saved.', 'Setting'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Setting'));
            }
        }
        $settingCategories = $this->Settings->SettingCategories->find('list', ['limit' => 200]);
        $types = $this->Settings->getListTypes();
        $this->set(compact('setting', 'settingCategories', 'types'));
        $this->set('_serialize', ['setting']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Setting id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $setting = $this->Settings->get($id);
        try {
            if ($this->Settings->delete($setting)) {
                Cache::delete('settings', 'long');
                $this->Flash->success(__('The {0} has been deleted.', 'Setting'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Setting'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Update method
     * @return \Cake\Network\Response|null
     */
    public function update()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            \Cake\Log\Log::write('debug', ">>>>>>Settings Update Works<<<<<<");
            $Settings = $this->request->data['setting'];
            foreach ($Settings as $key => $value) {
                $data = NULL;
                $data['value'] = $value['name'];
                $setting = $this->Settings->get($key, [
                    'contain' => []
                ]);
                $setting = $this->Settings->patchEntity($setting, $data);
                $this->Settings->save($setting);
            }
            Cache::delete('settings', 'long');
            $this->Flash->success(__('Settings saved.'));
        }
        $settingCategories = $this->Settings->SettingCategories
            ->find('all')
            ->contain([
                'Settings' => [
                    'sort' => [
                        'Settings.label'
                    ]
                ]
            ])
            ->order('SettingCategories.name');
        $this->set(compact( 'settingCategories'));
        $this->set('_serialize', ['setting']);
    }
}
