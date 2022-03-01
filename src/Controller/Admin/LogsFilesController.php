<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * LogsFiles Controller
 *
 * @property \App\Model\Table\LogsFilesTable $LogsFiles
 *
 * @method \App\Model\Entity\LogsFile[] paginate($object = null, array $settings = [])
 */
class LogsFilesController extends AppController
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
            'order' => ['LogsFiles.id' => 'DESC']
        ];
        $logsFiles = $this->paginate($this->LogsFiles);
        $this->set(compact('logsFiles'));
        $this->set('_serialize', ['logsFiles']);
    }

    /**
     * View method
     *
     * @param string|null $id Logs File id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $logsFile = $this->LogsFiles->get($id, [
            'contain' => []
        ]);

        $this->set('logsFile', $logsFile);
        $this->set('_serialize', ['logsFile']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $logsFile = $this->LogsFiles->newEntity();
        if ($this->request->is('post')) {
            $logsFile = $this->LogsFiles->patchEntity($logsFile, $this->request->getData());
            if ($this->LogsFiles->save($logsFile)) {
                $this->Flash->success(__('The {0} has been saved.', 'Logs File'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Logs File'));
            }
        }
        $this->set(compact('logsFile'));
        $this->set('_serialize', ['logsFile']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Logs File id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $logsFile = $this->LogsFiles->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $logsFile = $this->LogsFiles->patchEntity($logsFile, $this->request->getData());
            if ($this->LogsFiles->save($logsFile)) {
                $this->Flash->success(__('The {0} has been saved.', 'Logs File'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Logs File'));
            }
        }
        $this->set(compact('logsFile'));
        $this->set('_serialize', ['logsFile']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Logs File id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $logsFile = $this->LogsFiles->get($id);
        if ($this->LogsFiles->delete($logsFile)) {
            $this->Flash->success(__('The {0} has been deleted.', 'Logs File'));
        } else {
            $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Logs File'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function download($id){

        $logsFile = $this->LogsFiles->get($id);

        $filePath = $logsFile->file_dir.$logsFile->name_file;

        $this->response->file($filePath ,
            array(
                'download'=> true,
                'name'=> $logsFile->name_file
            )
        );

        return $this->response;
    }

}
