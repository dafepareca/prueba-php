<?php
namespace App\Controller\Asesor;

use App\Controller\AppController;
use App\Model\Entity\Log;
use App\Model\Table\TicketsStatusTable;
use Cake\Event\Event;

/**
 * Tickets Controller
 *
 * @property \App\Model\Table\TicketsTable $Tickets
 * @property \App\Model\Table\TicketsTypesTitlesTable $TicketsTypesTitles
 * @property \App\Controller\Component\EmailComponent $Email
 *
 * @method \App\Model\Entity\Ticket[] paginate($object = null, array $settings = [])
 */
class TicketsController extends AppController
{

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub

        $this->viewBuilder()->setTemplatePath('/Tickets');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->setConfig('unlockedActions', ['add']);
    }

    /**
    * Index method
    * @return \Cake\Network\Response|null
    */
    public function index()
    {
        $this->loadComponent('Search');
        $conditions = $this->Search->getConditions();

        $contain = ['Users','TicketsStatus'];
        if($this->Auth->user('solve_tickets')){
            $contain = ['Users','Solveds','Approveds', 'TicketsStatus','TicketsTypesErrors'];
            if(!isset($this->request->data['ticket_state_id'])) {
               # $conditions['ticket_state_id'] = TicketsStatusTable::PENDIENTE;
            }
        }else{
            $conditions['user_id'] = $this->Auth->user('id');
        }

        $this->paginate = [
            'conditions' => $conditions,
            'contain' => $contain,
            'order' => ['Tickets.id' => 'DESC']
        ];


        $ticketsStatus = $this->Tickets->TicketsStatus->find('list', ['limit' => 200, 'order' => 'id ASC']);
        $tickets = $this->paginate($this->Tickets);

        $this->set(compact('tickets'));
        $this->set(compact('ticketsStatus'));
        $this->set('_serialize', ['tickets','ticketsStatus']);

        if($this->Auth->user('solve_tickets')){
         $this->render('admin');
        }
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
            'contain' => ['Users', 'TicketsResources']
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
        $this->loadModel('TicketsTypesTitles');
        $ticket = $this->Tickets->newEntity();
        if ($this->request->is('post')) {
            if(empty($this->request->data['tickets_resources'][0]['img']['tmp_name'])){
                unset($this->request->data['tickets_resources'][0]);
            }
            if(empty($this->request->data['tickets_resources'][1]['img']['tmp_name'])){
                unset($this->request->data['tickets_resources'][1]);
            }
            if(empty($this->request->data['tickets_resources'][2]['img']['tmp_name'])){
                unset($this->request->data['tickets_resources'][2]);
            }

            $ticket = $this->Tickets->patchEntity($ticket, $this->request->getData());
            $ticket->user_id = $this->Auth->user('id');
            $ticket->ticket_state_id = TicketsStatusTable::DESARROLLO;


            if ($this->Tickets->save($ticket)) {
                /*if($ticket->ticket_type_error_id == 2){

                    $this->loadComponent('Email');
                    $this->Email->add('waltercabezasr@gmail.com', 'walter cabezas');
                    foreach ($ticket->tickets_resources as $file){
                        \Cake\Log\Log::alert('llega');
                        $fileDir = WWW_ROOT.'img'.DS.$file->file_dir.$file->img;
                        $this->Email->addAttachment([$file->img => $fileDir]);
                    }

                    $this->Email->send(
                        'Prueba ticket',
                        'default',
                        'accord',
                        [
                            'date' => date('Y/m/d'),
                            'name' => 'walter test',
                            'message' => $ticket->description,
                        ]
                    );

                }*/
                $this->Flash->success(__('The {0} has been saved.', 'Ticket'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Ticket'));
            }
        }

        $typesErrors = $this->Tickets->TicketsTypesErrors->find('list', ['limit' => 200]);
        $this->set(compact('ticket','typesErrors'));
        $this->set('_serialize', ['ticket']);

        $typesTitles = $this->TicketsTypesTitles->find('list', [
            'keyField' => 'title',
            'valueField' => 'title',
            'order' => ['TicketsTypesTitles.title' => 'ASC']
            ]);
        $this->set(compact('ticket','typesTitles'));
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
        $this->set(compact('ticket', 'users'));
        $this->set('_serialize', ['ticket']);
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
            $ticket->ticket_state_id = TicketsStatusTable::APROBADO;

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
}
