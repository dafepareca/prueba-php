<?php
namespace App\Controller\Asesor;

use App\Controller\AppController;
use App\Model\Entity\HistoryDetail;
use App\Model\Entity\HistoryNormalization;
use App\Model\Table\HistoryStatusesTable;

/**
 * Committees Controller
 *
 * @property \App\Model\Table\CommitteesTable $Committees
 * @property \App\Model\Table\AdjustedObligationsTable $AdjustedObligations
 * @property \App\Model\Table\AdjustedObligationsDetailsTable $AdjustedObligationsDetails
 * @property \App\Controller\Component\DaviviendaComponent $Davivienda
 *
 * @method \App\Model\Entity\Committee[] paginate($object = null, array $settings = [])
 */
class CommitteesController extends AppController
{

    /**
    * Index method
    * @return \Cake\Network\Response|null
    */
    public function index()
    {
        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();
        $conditions['asesor_id'] = $this->Auth->user('id');;
        $this->paginate = [
            'conditions' => $conditions,
            'contain' => ['Users', 'HistoryCustomers' => ['HistoryStatuses']],
            'order' => ['Committees.id' => 'DESC']
        ];
        $committees = $this->paginate($this->Committees);
        $this->set(compact('committees'));
        $this->set('_serialize', ['committees']);
    }

    /**
     * View method
     *
     * @param string|null $id Committee id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->loadModel('Obligations');

        $committee = $this->Committees->get($id, [
            'contain' => [
                'Users',
                'HistoryCustomers' => [
                    'HistoryNormalizations',
                    'Users',
                    'HistoryDetails' => ['TypeObligations']
                ]
            ]
        ]);

        /*$obligations = $this->Obligations->find()
            ->where(['customer_id' => $committee->history_customer->customer_id])
            ->contain([
                'TypeObligations'
            ])

            ->all();*/

        $this->set('committee', $committee);
       # $this->set('obligations', $obligations);
        $this->set('_serialize', ['committee','obligations']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $committee = $this->Committees->newEntity();
        if ($this->request->is('post')) {
            $committee = $this->Committees->patchEntity($committee, $this->request->getData());
            if ($this->Committees->save($committee)) {
                $this->Flash->success(__('The {0} has been saved.', 'Committee'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Committee'));
            }
        }
        $users = $this->Committees->Users->find('list', ['limit' => 200]);
        $historyCustomers = $this->Committees->HistoryCustomers->find('list', ['limit' => 200]);
        $this->set(compact('committee', 'users', 'historyCustomers'));
        $this->set('_serialize', ['committee']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Committee id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $committee = $this->Committees->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $committee = $this->Committees->patchEntity($committee, $this->request->getData());
            if ($this->Committees->save($committee)) {
                $this->Flash->success(__('The {0} has been saved.', 'Committee'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Committee'));
            }
        }
        $users = $this->Committees->Users->find('list', ['limit' => 200]);
        $historyCustomers = $this->Committees->HistoryCustomers->find('list', ['limit' => 200]);
        $this->set(compact('committee', 'users', 'historyCustomers'));
        $this->set('_serialize', ['committee']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Committee id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $committee = $this->Committees->get($id);
        if ($this->Committees->delete($committee)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Committee'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Committee'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function aceptar_comite($id){
        $this->autoRender = false;
        $this->response->type('json');

        $this->loadModel('Committees');
        $this->loadModel('AdjustedObligations');
        $this->loadModel('AdjustedObligationsDetails');

        $committee = $this->Committees->get($id, [
            'contain' => [
                'Users',
                'HistoryCustomers' => [
                    'HistoryNormalizations' => [
                        'conditions' => ['id' => $this->request->getData('normalizacion_id')]
                    ],
                    'HistoryDetails' => ['TypeObligations']
                ]
            ]
        ]);

        /** @var  $normalizations HistoryNormalization*/
        $normalizations = $committee->history_customer->history_normalizations[0];

        $this->AdjustedObligations->updateAll(
            [
                'approved_committee' => 1,
                'pending_committee' => 0,
                'coordinator_id' => $this->Auth->user('id'),
            ],
            [
                'id' => $committee->adjusted_obligation_id
            ]
        );

        $this->AdjustedObligationsDetails->updateAll(
            [
                'months_term' => $normalizations->term,
                'new_fee' => $normalizations->fee,
                'monthly_rate' => $normalizations->rate,
            ],
            [
                'adjusted_obligation_id' => $committee->adjusted_obligation_id
            ]
        );

        $this->Committees->HistoryCustomers->updateAll(
            ['history_status_id' => HistoryStatusesTable::ACEPTADA_COMITE],
            ['id' => $committee->history_id]
        );

        $this->Committees->updateAll(['resolved' => 1],['id' => $id]);
        $this->loadComponent('Davivienda');

        $negociacion = $this->AdjustedObligations->find()
            ->where(['id' => $committee->adjusted_obligation_id])
            ->contain(
                [
                    'AdjustedObligationsDetails'
                ]
            )
            ->first();

        if($negociacion->normalization){
            $this->Davivienda->generate_pdf_normalization($negociacion);
        }else{
            $this->Davivienda->generate_pdf($negociacion);
        }

        echo json_encode(
            [
                'success' => true,
                'message' => __('Negociación enviada al cliente.')
            ]
        );
    }

    public function rechazar_comite($id){
        $this->autoRender = false;
        $this->response->type('json');

        $this->loadModel('Committees');
        $this->loadModel('AdjustedObligations');
        $this->loadModel('AdjustedObligationsDetails');
        $this->loadModel('Obligations');

        $committee = $this->Committees->get($id, [
            'contain' => [
                'Users',
                'HistoryCustomers' => [
                    'HistoryNormalizations' => [
                        'conditions' => ['id' => $this->request->getData('normalizacion_id')]
                    ],
                    'HistoryDetails' => ['TypeObligations']
                ]
            ]
        ]);

        /** @var  $normalizations HistoryNormalization*/
        $normalizations = $committee->history_customer->history_normalizations[0];

        $obligations = [];
        /** @var  $obligation HistoryDetail*/
        foreach ($committee->history_customer->history_details as $obligation){
            if($obligation->selected) {
                $obligations[] = $obligation->obligation;
            }
        }

        $this->AdjustedObligations->deleteAll(
            [
                'id' => $committee->adjusted_obligation_id
            ]
        );

        $this->AdjustedObligationsDetails->deleteAll(
            [
                'adjusted_obligation_id' => $committee->adjusted_obligation_id
            ]
        );


        $this->Committees->HistoryCustomers->updateAll(
            ['history_status_id' => HistoryStatusesTable::RECHAZADA_COMITE],
            ['id' => $committee->history_id]
        );

        $this->Committees->updateAll(['resolved' => 1],['id' => $id]);

        echo json_encode(
            [
                'success' => true,
                'message' => __('Negociación rechazada.')
            ]
        );
    }

}
