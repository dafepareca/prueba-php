<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\AdjustedObligation;
use App\Model\Entity\Obligation;

/**
 * AdjustedObligations Controller
 *
 * @property \App\Model\Table\AdjustedObligationsTable $AdjustedObligations
 *
 * @method \App\Model\Entity\AdjustedObligation[] paginate($object = null, array $settings = [])
 */
class AdjustedObligationsController extends AppController
{

    /**
    * Index method
    * @return \Cake\Network\Response|null
    */
    public function index()
    {
        $data = $this->request->getQuery();
        if(empty($data['date_negotiation'])){
            $this->request->data['date_negotiation'] = date('Y-m-d').' - '.date('Y-m-d');
            $this->request->query['date_negotiation'] = date('Y-m-d').' - '.date('Y-m-d');
        }
        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();
        $conditions['pending_committee'] = 0;
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => [],
            'order' => ['AdjustedObligations.id' => 'ASC']
        ];
        $adjustedObligations = $this->paginate($this->AdjustedObligations);
        $this->set(compact('adjustedObligations'));
        $this->set('_serialize', ['adjustedObligations']);
    }

    /**
     * View method
     *
     * @param string|null $id Adjusted Obligation id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $adjustedObligation = $this->AdjustedObligations->get($id, [
            'contain' => ['AdjustedObligationsDetails']
        ]);

        $this->set('adjustedObligation', $adjustedObligation);
        $this->set('_serialize', ['adjustedObligation']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $adjustedObligation = $this->AdjustedObligations->newEntity();
        if ($this->request->is('post')) {
            $adjustedObligation = $this->AdjustedObligations->patchEntity($adjustedObligation, $this->request->getData());
            if ($this->AdjustedObligations->save($adjustedObligation)) {
                $this->Flash->success(__('The {0} has been saved.', 'Adjusted Obligation'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Adjusted Obligation'));
            }
        }
        $this->set(compact('adjustedObligation'));
        $this->set('_serialize', ['adjustedObligation']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Adjusted Obligation id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $adjustedObligation = $this->AdjustedObligations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $adjustedObligation = $this->AdjustedObligations->patchEntity($adjustedObligation, $this->request->getData());
            if ($this->AdjustedObligations->save($adjustedObligation)) {
                $this->Flash->success(__('The {0} has been saved.', 'Adjusted Obligation'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Adjusted Obligation'));
            }
        }
        $this->set(compact('adjustedObligation'));
        $this->set('_serialize', ['adjustedObligation']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Adjusted Obligation id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $adjustedObligation = $this->AdjustedObligations->get($id);
        if ($this->AdjustedObligations->delete($adjustedObligation)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Adjusted Obligation'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Adjusted Obligation'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function export(){
        $nameFile = date('Y-m-d H:m:s').'-negociaciones.csv';
        $filePath = TMP.'files/'.$nameFile;

        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();
        $conditions['pending_committee'] = 0;

        $obligations = $this->AdjustedObligations->find('all')
            ->contain(['AdjustedObligationsDetails'])
            ->where($conditions)->all();

        $fp = fopen($filePath, 'w');
        $headers = [
            'Fecha Consulta',
            'Tipo ID',
            'ID',
            'Obligación',
            'Ingresos',
            'Capacidad de Pago',
            'Email',
            'Saldo Total',
            'Pago Minimo',
            'Cuota',
            'Estrategia',
            'Cuota Proyctada',
            'Plazo',
            'Tasa Efectiva',
            'Tasa Nominal',
            'Usuario',
            'Abono Cliente',
            'Fecha Abono Cliente',
            'Sucursal',
            'Fecha Documentación',
        ];
        fputcsv($fp, $headers);
        /** @var  $obligation Obligation*/
        foreach ($obligations as $obligation) {
            foreach($obligation->adjusted_obligations_details as $detail){
                $fields = [
                    'fecha' => $obligation->date_negotiation,
                    'type_identification' => $obligation->type_identification,
                    'identification' => $obligation->identification,
                    'obligation' => $detail->obligation,
                    'customer_revenue' => $obligation->customer_revenue,
                    'customer_paid_capacity' => $obligation->customer_paid_capacity,
                    'customer_email' => $obligation->customer_email,
                    'total_debt' => $detail->total_debt,
                    'previous_minimum_payment' => $detail->previous_minimum_payment,
                    'initial_fee' => $detail->new_fee,
                    'strategy' => h($detail->strategy),
                    'Cuota_Proyctada' => '',
                    'months_term' => $detail->months_term,
                    'annual_effective_rate' => $detail->annual_effective_rate,
                    'nominal_rate' => $detail->nominal_rate,
                    'user_dataweb' => h($obligation->user_dataweb),
                    'payment_agreed' => $obligation->payment_agreed,
                    'documentation_date' => $obligation->documentation_date,
                    'office_name' => $obligation->office_name,
                    'documentation_date_2' => $obligation->documentation_date,
                ];
            }
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
