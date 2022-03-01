<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;

/**
 * IpsGroups Controller
 *
 * @property \App\Model\Table\IpsGroupsTable $IpsGroups
 * @property \App\Controller\Component\SearchComponent $Search
 */
class IpsGroupsController extends AppController
{
    public $accessGroup = null;
    public function beforeFilter(Event $event){
        parent::beforeFilter($event);
        $access_group_id = $this->request->getQuery('access_group_id');
        if(isset($access_group_id) and !empty($access_group_id)){
            $this->accessGroup = $this->IpsGroups->AccessGroups->get($access_group_id, [
                'fields' => [
                    'AccessGroups.name',
                    'AccessGroups.id'
                ],
            ]);
            $this->set('accessGroup', $this->accessGroup);
        }else{
            $this->Flash->error(__('No Access Group selected. Please, try again.'));
            return $this->redirect(array_merge(['controller' => 'access_groups', 'action' => 'index']));
        }
    }
    /**
    * Index method
    * @return \Cake\Network\Response|null
    */
    public function index()
    {
        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();
        $conditions['IpsGroups.access_group_id'] = $this->accessGroup->id;
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [],
            'order' => ['IpsGroups.id' => 'ASC']
        ];
        $ipsGroups = $this->paginate($this->IpsGroups);
        $this->set(compact('ipsGroups'));
        $this->set('_serialize', ['ipsGroups']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ipsGroup = $this->IpsGroups->newEntity();
        if ($this->request->is('post')) {
            $ipsGroup = $this->IpsGroups->patchEntity($ipsGroup, $this->request->getData());
            $ipsGroup->access_group_id = $this->accessGroup->id;
            if ($result = $this->IpsGroups->save($ipsGroup)) {
                $this->Flash->success(__('The {0} has been saved.', 'Ips Group'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Ips Group'));
            }
        }
        $this->set(compact('ipsGroup'));
        $this->set('_serialize', ['ipsGroup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ips Group id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ipsGroup = $this->IpsGroups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ipsGroup = $this->IpsGroups->patchEntity($ipsGroup, $this->request->getData());
            $ipsGroup->access_group_id = $this->accessGroup->id;
            if ($this->IpsGroups->save($ipsGroup)) {
                $this->Flash->success(__('The {0} has been saved.', 'Ips Group'));
                return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Ips Group'));
            }
        }
        $this->set(compact('ipsGroup'));
        $this->set('_serialize', ['ipsGroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ips Group id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ipsGroup = $this->IpsGroups->get($id);
        try {
            if ($this->IpsGroups->delete($ipsGroup)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Ips Group'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Ips Group'));
            }
        }catch (Exception $e){
            $this->Flash->error(__("Delete failed. ").$e->getMessage());
        }
        return $this->redirect(array_merge(['action' => 'index'], $this->request->getQuery()));
    }}
