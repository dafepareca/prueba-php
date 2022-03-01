<?php
namespace App\Controller\Asesor;

use App\Controller\BaseCustomersController;
use App\Model\Entity\Charge;
use App\Model\Entity\Customer;
use App\Model\Entity\Obligation;
use App\Model\Table\AccessTypesActivitiesTable;
use App\Model\Table\ChargesTable;
use App\Model\Table\HistoryStatusesTable;
use App\Model\Table\TypeObligationsTable;
use Cake\Cache\Cache;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerTypeIdentificationsTable $CustomerTypeIdentifications
 * @property \App\Model\Table\WorkActivitysTable $WorkActivitys
 * @property \App\Model\Table\CertificatesTable $Certificates
 * @property \App\Model\Table\NormalizationReasonsTable $NormalizationReasons
 * @property \App\Model\Table\QueriesCustomersTable $QueriesCustomers
 * @property \App\Model\Table\HistoryCustomersTable $HistoryCustomers
 * @property \App\Model\Table\ObligationsTable $Obligations
 */
class CustomersController extends BaseCustomersController
{

    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }


    public function information(){

        $this->autoRender = false;

        if (($posts = Cache::read($this->Auth->user('session_id').'customer')) === false) {
            $this->Flash->error(__('Debe consultar el cliente...'));
            return $this->redirect(['controller'=> 'customers', 'action' => 'consult']);
        }

        $this->loadModel('WorkActivitys');
        $this->loadModel('NormalizationReasons');
        $this->loadModel('Certificates');
        $this->loadModel('Obligations');


        /** @var  $customer Customer*/
        $customer = Cache::read($this->Auth->user('session_id').'customer');
        $obligations = $this->Obligations->find()
            ->where(['restructuring' => 0,'customer_id' => $customer->id])
            ->contain([
                'TypeObligations'
            ])
            ->all();

        $noObligations = $this->Obligations->find()
            ->where(['restructuring' => 1,'customer_id' => $customer->id])
            ->contain([
                'TypeObligations'
            ])
            ->all();

        $this->set(compact('obligations'));
        $this->set(compact('noObligations'));

        $workActivitys = $this->WorkActivitys->find('list')->toArray();
        $normalizationReasons = $this->NormalizationReasons->find('list')->toArray();
        $certificates = $this->Certificates->find('list')->toArray();

        $this->set(compact('workActivitys'));
        $this->set(compact('normalizationReasons'));
        $this->set(compact('certificates'));

        $this->viewBuilder()->setTemplatePath('/Customers');
        $this->render('consult');
    }

    public function update($id){
        $this->autoRender = false;
        $this->response->type('json');


        if ($this->request->is(['patch', 'post', 'put'])) {
            $customer = $this->Customers->get($id);

            $customer = $this->Customers->patchEntity($customer, $this->request->getData());
            if ($this->Customers->save($customer)) {
                $result = [
                    'success' => true,
                    'data' => [],
                    'message' => __('Información actualizada')
                ];

                $customer = Cache::read($this->Auth->user('session_id').'customer');
                $customer->name = $this->request->getData('name');
                $customer->email = $this->request->getData('email');
                Cache::write($this->Auth->user('session_id').'customer',$customer);

            } else {
                $result = [
                    'success' => false,
                    'data' => [],
                    'message' => __('Error actualizando la información')
                ];
            }
        }

        echo json_encode($result);

    }



}
