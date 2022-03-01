<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Controller\Component\SearchComponent;

/**
 * Audits Controller
 *
 * @property \App\Model\Table\AuditsTable $Audits
 * @property \App\Controller\Component\SearchComponent $Search
 */

class AuditsController extends AppController
{
    public $helpers = ['AuditLog'];
    /**
     * Index method
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $data = $this->request->getQuery();
        if(empty($data['created'])){
            $this->request->data['created'] = date('Y-m-d').' - '.date('Y-m-d');
            $this->request->query['created'] = date('Y-m-d').' - '.date('Y-m-d');
        }
        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();

        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [
                'AccessLogs' => [
                    'Users' => [
                       'Roles'
                    ]
                ]
            ],
            'order' => ['Audits.created' => 'DESC']
        ];
        $audits = $this->paginate($this->Audits);
        $events = $this->Audits->find('list', [
            'keyField' => 'event',
            'valueField' => 'event',
            'limit' => 200,
            'order' => 'event'
        ]);
        $resources = $this->Audits->find('list', [
            'keyField' => 'model',
            'valueField' => 'model',
            'limit' => 200,
            'group' => 'model',
            'order' => 'model'
        ]);
        $this->set(compact('accessActivities', 'resources', 'events'));
        $this->set(compact('audits'));
        $this->set('_serialize', ['audits']);
    }

    /**
     * View method
     *
     * @param string|null $id Audit id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $audit = $this->Audits->get($id, [
            'contain' => ['AuditDeltas']
        ]);
        $this->set('audit', $audit);
        $this->set('_serialize', ['audit']);
    }
}