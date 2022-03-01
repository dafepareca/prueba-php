<?php
namespace App\Controller\Super;

use App\Controller\AppController;
use App\Controller\Component\SearchComponent;
use App\Model\Entity\Audit;
use App\Model\Entity\Setting;
use App\Model\Table\RolesTable;
use Cake\Cache\Cache;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

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

    public function export(){

        $nameFile = date('d-m-Y_H-m-s').'-auditoria.csv';
        $filePath = TMP.'files/'.$nameFile;

        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();

        $audits = $this->Audits->find('all')
            ->contain(
                [
                    'AccessLogs' => [
                        'Users' => [
                           'Roles'
                        ]
                    ],
                    'AuditDeltas'
                ]
            )
            ->where($conditions)->all();

        $fp = fopen($filePath, 'w');
        $headers = [
            'Usuario',
            'Fecha de cambio',
            'Campo modificado',
            'Valor anterior',
            'Valor Nuevo'

        ];
        fputcsv($fp, $headers);

        $condiciones = Cache::read('conditions', 'long');

        foreach ($audits as $audit) {
            $accessLog = $audit->access_log;
            if($accessLog->user->role_id == \App\Model\Table\RolesTable::Super){
                foreach ($audit->audit_deltas as $auditDeltas){
                    $valorAnterior = $auditDeltas->old_value;
                    $valorNuevo = $auditDeltas->new_value;
                    $campo = $audit->model .' / '. $auditDeltas->property_name;
                }
                if ($audit->model == 'Settings'){
                    $settings = TableRegistry::get('settings');
                    $setting_categories = TableRegistry::get('setting_categories');
                    $settings = $settings->find()
                    ->where(['id' => $audit->entity_id])
                    ->first();
                    $setting_categories = $setting_categories->find()
                    ->where(['id' => $settings->setting_category_id])
                    ->first();
                    $campo = $audit->model .' / '.$setting_categories->name .' / '. $settings->label;
                }
                
                $fields = [
                    'Usuario' => $audit->access_log->user->name,
                    'fecha' => $audit->created->format('d-m-Y H:i:s'),
                    'campo' => $campo,
                    'valor_anterior' => $valorAnterior,
                    'valor_nuevo' => $valorNuevo
                ];
                fputcsv($fp, $fields);
            }    
        }
        fclose($fp);
        $this->response->file($filePath ,
            array(
                'download'=> true,
                'name'=> $nameFile
            )
        );

        return $this->response;

    }
}