<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Model\Entity\Ticket;
use App\Model\Table\TicketsStatusTable;

/**
 * Tickets Controller
 *
 * @property \App\Model\Table\TicketsTable $Tickets
 *
 * @method \App\Model\Entity\Ticket[] paginate($object = null, array $settings = [])
 */
class TicketsController extends AppController
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
            'contain' => ['Users','Solveds','Approveds', 'TicketsStatus','TicketsTypesErrors'],
            'order' => ['Tickets.id' => 'DESC']
        ];
        $tickets = $this->paginate($this->Tickets);
        $ticketsStatus = $this->Tickets->TicketsStatus->find('list', ['limit' => 200, 'order' => 'id ASC']);
        $typesErrors = $this->Tickets->TicketsTypesErrors->find('list', ['limit' => 200]);
        $this->set(compact('tickets'));
        $this->set(compact('ticketsStatus'));
        $this->set(compact('typesErrors'));
        $this->set('_serialize', ['tickets','ticketsStatus']);
    }

    /**
     * View method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => ['Users','Solveds','Approveds', 'TicketsStatus', 'TicketsResources']
        ]);

        $this->set('ticket', $ticket);
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ticket = $this->Tickets->newEntity();
        if ($this->request->is('post')) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->getData());
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The {0} has been saved.', 'Ticket'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Ticket'));
            }
        }
        $users = $this->Tickets->Users->find('list', ['limit' => 200]);
        $ticketsStatus = $this->Tickets->TicketsStatus->find('list', ['limit' => 200]);
        $this->set(compact('ticket', 'users', 'ticketsStatus'));
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->getData());
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The {0} has been saved.', 'Ticket'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Ticket'));
            }
        }
        $users = $this->Tickets->Users->find('list', ['limit' => 200]);
        $ticketsStatus = $this->Tickets->TicketsStatus->find('list', ['limit' => 200]);
        $this->set(compact('ticket', 'users', 'ticketsStatus'));
        $this->set('_serialize', ['ticket']);
    }


    /**
     * solve method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function solve($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->getData());

            $ticket->solved_by = $this->Auth->user('id');
            $ticket->ticket_state_id = TicketsStatusTable::REVISION;

            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The {0} has been saved.', 'Ticket'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Ticket'));
            }
        }
        $users = $this->Tickets->Users->find('list', ['limit' => 200]);
        $ticketsStatus = $this->Tickets->TicketsStatus->find('list', ['limit' => 200]);
        $this->set(compact('ticket', 'users', 'ticketsStatus'));
        $this->set('_serialize', ['ticket']);
        $this->viewBuilder()->setTemplatePath('/Tickets');
    }

    /**
     * solve method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function developing($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->getData());

            $ticket->solved_by = $this->Auth->user('id');
            $ticket->ticket_state_id = TicketsStatusTable::DESARROLLO;

            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The {0} has been saved.', 'Ticket'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Ticket'));
            }
        }

        $this->set(compact('ticket', 'users', 'ticketsStatus'));
        $this->set('_serialize', ['ticket']);
        $this->viewBuilder()->setTemplatePath('/Tickets');
    }

    /**
     * Delete method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ticket = $this->Tickets->get($id);
        if ($this->Tickets->delete($ticket)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Ticket'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Ticket'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function approved($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ticket = $this->Tickets->get($id);
        $ticket->approved_by = $this->Auth->user('id');
        $ticket->ticket_state_id = TicketsStatusTable::APROBADO;
        if($this->Tickets->save($ticket)){
            $this->Flash->success(__('The {0} has been saved.', 'Ticket'));
        } else {
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Ticket'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function export(){
        $nameFile = 'tickets.csv';
        $filePath = TMP.'files/'.$nameFile;

        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();

        $tickets = $this->Tickets->find('all')
            ->contain(
                [
                    'Users','Solveds','Approveds', 'TicketsStatus','TicketsTypesErrors'
                ]
            )
            ->where($conditions)->all();

        $fp = fopen($filePath, 'w');
        $headers = [
            utf8_decode('Cédula'),
            utf8_decode('Título'),
            'Prioridad',
            'Tipo',
            'Estado',
            utf8_decode('Fecha Creación')
        ];
        fputcsv($fp, $headers);
        /** @var  $ticket Ticket*/
        foreach ($tickets as $ticket) {
            $fields = [
                'cedula' => utf8_decode($ticket->customer_id),
                'titulo' => utf8_decode($ticket->title),
                'prioridad' => $ticket->priorities[$ticket->priority],
                'tipo' => $ticket->tickets_types_error->type,
                'estado' => utf8_decode($ticket->tickets_status->state),
                'fecha' => (!empty($ticket->created))?utf8_decode($ticket->created->format('Y-m-d H:i:s')):""
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
