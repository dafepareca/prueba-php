<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
// use App\Model\Entity\AdjustedObligation;
// use App\Model\Entity\AdjustedObligationsDetail;
// use App\Model\Entity\HistoryCustomer;
// use App\Model\Entity\HistoryNormalization;
// use App\Model\Entity\HistoryStatus;
// use App\Model\Entity\Log;
// use App\Model\Entity\Obligation;
// use App\Model\Table\HistoryStatusesTable;
// use App\Model\Table\TypeObligationsTable;
use Cake\Log\Log;
use Cake\Cache\Cache;

/**
 * Rejected Controller
 *
 * @property \App\Model\Table\RejectedTable $Rejected
 * @property \App\Model\Table\TypeRejectedTable $TypeRejected
 *
 */
class RejectedController extends AppController
{

    // public function index1()
    // {

    //     $this->set('rejected', $this->Rejected->find('all'));
    // }

    // public function index()
    // {
    //     $query = $this->Rejected->find();
    //        $this->set('rejected', $query
    //         ->select([
    //             'Rejected.type_rejected_id'
    //             ,'TypeRejected.description'
    //             // ,'count' => $query->func()->count('Rejected.type_rejected_id')


    //         ])
    //            ->contain(['TypeRejected'])
    //         //    ->group('Rejected.type_rejected_id')
    //            ->order(['Rejected.type_rejected_id' => 'ASC'])
    //         //->limit(8);
    //        );
    // }


    public function index () 
    {
        // $this->loadModel('TypeRejected');
        $this->loadComponent('Search');
        if(empty($this->request->query['created'])){
            $this->request->data['created'] = date('Y-m-d').' - '.date('Y-m-d');
            $this->request->query['created'] = date('Y-m-d').' - '.date('Y-m-d');
        }
        $conditions = $this->Search->getConditions();
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['Users', 'TypeRejected', 'HistoryCustomers'],
            'order' => ['Rejected.id' => 'DESC']
        ];
        $rejected = $this->paginate($this->Rejected);

        $typeRejected = $this->Rejected->TypeRejected->find()->select(['id', 'description']);
        $typeRejectedList = [];
        foreach ($typeRejected as $listRejected) {
            $typeRejectedList[$listRejected->id] = $listRejected->description;
        }

        $this->set(compact('rejected', 'typeRejectedList'));
        $this->set('_serialize', ['rejected']);
    }


    public function view($id = null)
    {
        $rejected = $this->Rejected->get($id, [
            'contain' => ['Users', 'CustomerTypeIdentifications', 'TypeRejected', 'HistoryCustomers']
        ]);

        $this->set('rejected', $rejected);
        $this->set('_serialize', ['rejected']);
    
    }

    public function export(){

        $nameFile = date('d-m-Y_H-m-s').'-rechazos.csv';
        $filePath = TMP.'files/'.$nameFile;

        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();

        $rejected = $this->Rejected->find('all')
            ->contain([
                'Users' => [
                    'fields' => ['id', 'identification', 'name', 'email']
                ],
                'CustomerTypeIdentifications',
                'TypeRejected',
                'HistoryCustomers' => [
                    'HistoryDetails',
                    'HistoryStatuses',
                    'HistoryPaymentVehicles',
                    'HistoryNormalizations',
                    'HistoryPunishedPortfolios'
                ] 
            ])
            ->where($conditions)->all();

        $fp = fopen($filePath, 'w');
        $headers = [
            utf8_decode('Rechazo Fecha'),
            utf8_decode('Rechazo ID'),
            utf8_decode('Tipo Rechazo'),
            utf8_decode('Usuario'),
            utf8_decode('Usuario ID'),
            utf8_decode('Usuario Email'),
            utf8_decode('Cliente Tipo Identificación'),
            utf8_decode('Cliente ID'),
            utf8_decode('Detalle'),
        ];
        fputcsv($fp, $headers);

        $condiciones = Cache::read('conditions', 'long');

        foreach ($rejected as $register) {

            $fields = [
                'rechazo_fecha' => $register->created->format('Y-m-d H:i:s'),
                'rechazo_id' => $register->history_customer_id,
                'tipo_rechazo' => utf8_decode($register->type_rejected->description),
                'usuario' => $register->user->name,
                'usuario_id' => $register->user->identification,
                'usuario_email' => $register->user->email,
                'cliente_tipo_id' => utf8_decode($register->customer_type_identification->type),
                'cliente_id' => $register->customer_identification,
                'detalle' => utf8_decode($register->details),
            ];

            fputcsv($fp, $fields);
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
