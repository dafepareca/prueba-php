<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\Entity\Charge;
use App\Model\Entity\Condition;
use App\Model\Entity\Customer;
use App\Model\Entity\HistoryStatus;
use App\Model\Entity\LogTransactional;
use App\Model\Entity\LogCommercial;
use App\Model\Entity\LogRediferido;
use App\Model\Table\Log;
//use App\Model\Entity\Log;
use App\Model\Entity\Obligation;
use App\Model\Table\ChargesTable;
use App\Model\Table\ConditionsTable;
use App\Model\Table\HistoryStatusesTable;
use App\Model\Table\TicketsStatusTable;
use App\Model\Table\TypeObligationsTable;
use App\Model\Table\NegotiationReasonTable;
use App\Model\Table\TypesConditionsTable;
use App\Model\Table\ObligationsTable;
use Cake\Cache\Cache;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use App\Model\Table\RolesTable;
use App\Model\Table\UserStatusesTable;
use Cake\I18n\I18n;
use Cake\Network\Session;
use Cake\Database\Exception;
use App\Controller\LogTransaccionController;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 *
 * @property \App\Model\Table\BusinessUnitsTable $BusinessUnits
 * @property \App\Model\Table\AdminPermissionsTable $AdminPermissions
 * @property \App\Model\Table\AccessActivitiesTable $AccessActivities
 * @property \App\Model\Table\AnnualEffectiveRateTdcTable $AnnualEffectiveRateTdc
 * @property \App\Model\Table\AnnualEffectiveRateUvrTable $AnnualEffectiveRateUvr
 * @property \App\Model\Table\ChargesTable Charges
 * @property \App\Model\Table\LogsTable $Logs
 * @property \App\Model\Table\HistoryCustomersTable $HistoryCustomers
 * @property \App\Model\Table\HistoryDetailsTable $HistoryDetails
 * @property \App\Model\Table\HistoryNormalizationsTable $HistoryNormalizations
 * @property \App\Model\Table\HistoryPunishedPortfoliosTable $HistoryPunishedPortfolios
 * @property \App\Model\Table\HistoryPaymentVehiclesTable $HistoryPaymentVehicles
 * @property \App\Model\Table\TicketsTable $Tickets
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\CustomerTypeIdentifications $customer_type_identification
 * @property \App\Model\Entity\AdjustedObligation[] $adjusted_obligations
 * @property \App\Model\Entity\HistoryCustomer[] $history_customers
 */
class AppController extends Controller
{
   

    public $session;
    public $helpers = [
        'Form' => [
            'className' => 'Bootstrap.Form',
            'columns' => [
                'md' => [
                    'label' => 4,
                    'input' => 8,
                    'error' => 0
                ],
            ],
           'useCustomFileInput' => true
        ],
        'Html' => [
            'className' => 'Bootstrap.Html',
            'templates' => [
                'icon' => '<i aria-hidden="true" class="fa fa-fw fa-{{type}}{{attrs.class}}"{{attrs}}></i>',
            ]
        ],
        'Modal' => [
            'className' => 'Bootstrap.Modal'
        ],
        'Navbar' => [
            'className' => 'Bootstrap.Navbar'
        ],
        'Paginator' => [
            'className' => 'Bootstrap.Paginator'
        ],
        'Panel' => [
            'className' => 'Bootstrap.Panel'
        ]
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {

        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $this->session = new Session();

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        $this->loadComponent('Security');
        $this->loadComponent('Csrf',array('httpOnly'=>false));

        $this->loadComponent('Auth', [
            'authorize' => [
                'Controller',
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login',
                'prefix' => false,
                'admin' => false
            ],
            //'authError' => 'Invalid username or password',
            'loginRedirect' => '/',
            'logoutRedirect' => array(
                'controller' => 'Users',
                'action' => 'login',
                'prefix' => false,
                'admin' => false
            ),
            /*
            'unauthorizedRedirect' => [
                'controller' => 'users',
                'action' => 'login',
                'prefix' => false,
                'admin' => false
            ],
            */
            'authenticate' => [
                'Form' => [
                    'passwordHasher' => [
                        'className' => 'Legacy',
                    ],
                    'fields' => [
                        'username' => 'email',
                        'password' => 'token'
                    ],
                    'scope' => [
                        'Users.user_status_id' => UserStatusesTable::Active,
                    ],
                    'contain' => [
                        'Roles' => [
                            'fields' => [
                                'Roles.name',
                                'Roles.prefix'
                            ]
                        ],
                        'AccessGroups' => [
                            'IpsGroups' => [
                                'fields' => [
                                    'IpsGroups.ip_address',
                                    'IpsGroups.access_group_id'
                                ]
                            ],
                            'fields' => [
                                'AccessGroups.id',
                                'AccessGroups.name',
                                'AccessGroups.all_ips'
                            ]
                        ],
                        'Attachments',
                        'Business' => [
                            'fields' => [
                                'Business.id',
                                'Business.name',
                                'Business.code_of_recovery'
                            ]
                        ]
                    ]
                ],
            ],
            'flash' => [
                'element' => 'auth',
                'key' => 'flash',
                'params' => ['class' => 'error']
            ],
        ]);
    }

    public function beforeFilter(Event $event){
        $user = $this->Auth->user();


        if(!empty($user) &&  empty($this->Auth->user('password_expiration_date'))){
            $this->Auth->logout();
            return $this->redirect();
        }
        if(!empty($user) && strtotime($this->Auth->user('password_expiration_date')->format('Y-m-d')) < strtotime('now')){
            $this->loadModel('Users');
            $user = $this->Users->get($user['id'], [
                'contain' => ['Attachments', 'Roles']
            ]);

            $this->set('update_password', true);
            $this->set('user', $user);

        }

        if($this->Auth->user('role_id') == RolesTable::Super){
            // $this->loadModel('AnnualEffectiveRateTdc');
            $this->loadModel('AnnualEffectiveRateUvr');

            // TDC Params
            // $annualEffectiveRateTdc = $this->AnnualEffectiveRateTdc->find()
            //     ->where([
            //         'MONTH(fecha)' => date('m'),
            //         'year(fecha)' => date('Y')
            //     ])
            //     ->first();

            // if(!$annualEffectiveRateTdc){
            //     $this->set('update_rate_tdc', true);
            // }

            // UVR Params
            $annualEffectiveRateUvr = $this->AnnualEffectiveRateUvr->find()
                ->where([
                    'MONTH(month_date)' => date('m'),
                    'year(month_date)' => date('Y')
                ])
                ->first();

            if(!$annualEffectiveRateUvr){
                $this->set('update_rate_uvr', true);
            }

        }


        if($this->Auth->user('role_id') == RolesTable::Coordinador || $this->Auth->user('role_id') == RolesTable::Asesor){
            // $this->loadModel('AnnualEffectiveRateTdc');
            $this->loadModel('AnnualEffectiveRateUvr');

            // $annualEffectiveRateTdc = $this->AnnualEffectiveRateTdc->find()
            //     ->where([
            //         'MONTH(fecha)' => date('m'),
            //         'year(fecha)' => date('Y')
            //     ])
            //     ->first();
            
            $annualEffectiveRateUvr = $this->AnnualEffectiveRateUvr->find()
                ->where([
                    'MONTH(month_date)' => date('m'),
                    'year(month_date)' => date('Y')
                ])
                ->first();
            
            $numTickets = 0;

            if(isset($user['solve_tickets'])) {
                if ($this->Auth->user('solve_tickets')) {
                    $this->loadModel('Tickets');
                    $numTickets = $this->Tickets->find()->where(['ticket_state_id' => TicketsStatusTable::PENDIENTE])->count();
                }
            }

            $this->set('numTickets',$numTickets);

            // if(!$annualEffectiveRateTdc && date('j') > 5){
            //     $this->set('update_rate_tdc', true);
            // }
            
            if(!$annualEffectiveRateUvr && date('j') > 5){
                $this->set('update_rate_uvr', true);
            }


        }


        if($this->Auth->user('role_id') == RolesTable::Admin) {
            $this->loadModel('AdminPermissions');
            $permissions = $this->AdminPermissions->find()->where(['user_id' => $this->Auth->user('id')])->first();
            if ($permissions) {
                $this->set('permissions', $permissions);
            }
        }

        $this->set('current_user', $this->Auth->user());
        $this->Auth->allow('display');

        $this->loadModel('Charges');

        /** @var  $charge Charge*/
        $charge = $this->Charges->find()->select(['id','created'])->where(['state' => ChargesTable::ACTIVO,'type_charge'=>1])->first();
        $lastUploadDate = $charge->created->format('d/m/y - H:i');

        $this->set('lastUploadDate', $lastUploadDate);

        $settings = Cache::read('settings', 'long');
        if (empty($settings)) {
            $settings = $this->fetchSettings();
            Cache::write('settings', $settings, 'long');
        }

        $conditions = Cache::read('conditions', 'long');
        if (empty($conditions)) {
            $conditions = $this->fetchConditios();
            Cache::write('conditions', $conditions, 'long');
        }
        Configure::write($settings);
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    public function isAuthorized($user = null) {
        return $this->_checkAuth();
    }

    function _checkAuth(){
        $allowAll = array(
            'pages/display',
            'users/update_password',
        );



        $allowAdmin = array(
            'users/dashboard',
            'users/index',
            'users/add',
            'users/edit',
            'users/view',
            'users/delete',
            'users/archive',
            'users/export',
            'asesor/index',
            'asesor/add',
            'asesor/edit',
            'asesor/view',
            'asesor/delete',
            'asesor/archive',
            'asesor/reset_password',
            'users/reset_password',
            'coordinador/add',
            'coordinador/index',
            'coordinador/edit',
            'coordinador/view',
            'coordinador/delete',
            'coordinador/archive',
            'coordinador/reset_password',
            'customers/index',
            'customers/add',
            'customers/edit',
            'customers/delete',
            'customers/view',
            'adjustedobligations/index',
            'adjustedobligations/view',
            'adjustedobligations/export',
            'business/index',
            'business/view',
            'business/reportusers',
            'pages/reports',
            'logs/index',
            'logs/view',
            'logs/export',
            'rejected/index',
            'rejected/view',
            'rejected/export',

            'tickets/delete',
            'tickets/index',
            'tickets/view',
            'tickets/add',
            'tickets/solve',
            'tickets/developing',
            'tickets/approved',
            'tickets/export',

            'charges/index',
            'charges/add',
            'charges/delete',
            'logsfiles/index',
            'logsfiles/download',

            'trainings/index',
            'trainings/add',
            'trainings/delete',
            'trainings/view',
            'trainings/edit',

            'trainingresources/index',
            'trainingresources/add',
            'trainingresources/delete',
            'trainingresources/view',
            'trainingresources/edit',
        );

        $allowSuper = array(
            'users/dashboard',
            'users/index',
            'users/add',
            'users/edit',
            'users/view',
            'users/delete',
            'users/archive',
            'admins/dashboard',
            'admins/index',
            'admins/add',
            'admins/edit',
            'admins/view',
            'admins/delete',
            'admins/archive',
            'admins/reset_password',
            'users/reset_password',
            'asesor/add',
            'asesor/edit',
            'asesor/view',
            'asesor/delete',
            'asesor/archive',
            'coordinador/edit',
            'coordinador/view',
            'coordinador/delete',
            'coordinador/archive',
            'customers/index',
            'customers/add',
            'customers/edit',
            'customers/delete',
            'customers/view',
            'business/index',
            'business/view',
            'business/add',
            'business/edit',
            'business/delete',
            'accessgroups/index',
            'accessgroups/view',
            'accessgroups/add',
            'accessgroups/edit',
            'accessgroups/delete',
            'ipsgroups/index',
            'ipsgroups/add',
            'ipsgroups/edit',
            'ipsgroups/delete',

            'settings/update',
            
            'accesslogs/index',
            'accesslogs/view',
            'accessactivities/index',
            'audits/index',
            'audits/view',
            'audits/export',
            'typeobligations/index',
            'typeobligations/view',
            'typeobligations/edit',
            'statereasons/index',
            'statereasons/view',
            'statereasons/edit',
            'statereasons/delete',
            'statereasons/add',

            'customertypeidentifications/index',
            'customertypeidentifications/view',
            'customertypeidentifications/edit',
            'customertypeidentifications/delete',
            'customertypeidentifications/add',
            'cityoffices/index',
            'cityoffices/view',
            'cityoffices/add',
            'cityoffices/delete',
            'cityoffices/edit',

            'offices/index',
            'offices/view',
            'offices/add',
            'offices/delete',
            'offices/edit',

            'schedules/index',
            'schedules/view',
            'schedules/add',
            'schedules/delete',
            'schedules/edit',

            'legalcodes/index',
            'legalcodes/view',
            'legalcodes/add',
            'legalcodes/delete',
            'legalcodes/edit',

            'cndcodes/index',
            'cndcodes/view',
            'cndcodes/add',
            #'cndcodes/delete',
            'cndcodes/edit',

            'modalmessage/index',
            'modalmessage/view',
            #'modalmessage/add',
            #'modalmessage/delete',
            'modalmessage/edit',

            'negotiationreason/index',
            'negotiationreason/view',
            'negotiationreason/add',
            #'negotiationreason/delete',
            'negotiationreason/edit',

            // 'annualeffectiveratetdc/delete',
            // 'annualeffectiveratetdc/index',
            // 'annualeffectiveratetdc/view',
            // 'annualeffectiveratetdc/add',
            // 'annualeffectiveratetdc/edit',

            'annualeffectiverateuvr/delete',
            'annualeffectiverateuvr/index',
            'annualeffectiverateuvr/view',
            'annualeffectiverateuvr/add',
            'annualeffectiverateuvr/edit',

            'productcodes/delete',
            'productcodes/index',
            'productcodes/view',
            'productcodes/add',
            'productcodes/edit',

            'conditions/delete',
            'conditions/index',
            'conditions/view',
            'conditions/add',
            'conditions/edit',
            'conditions/sort',

            'tickets/delete',
            'tickets/index',
            'tickets/view',
            'tickets/add',
            'tickets/solve',
            'tickets/developing',
            'tickets/approved',

            'valuestages/index',
            'valuestages/view',
            'valuestages/add',
            'valuestages/delete',
            'valuestages/edit',
            
            'settings/add',
            'settings/edit',
            'settings/index',

        );

        if (Configure::read('debug')) {
            $allowSuper = array_merge($allowSuper, [
                'roles/index',
                'roles/add',
                'roles/edit',
                'roles/delete',
                'roles/view',
                'settingcategories/index',
                'settingcategories/add',
                'settingcategories/edit',
                'settingcategories/delete',
                'settings/delete',
            ]);
        }


        $allowAsesor = array(
            'customers/consult',
            'obligations/pagototalvehiculo',
            'obligations/validarofertavehiculo',
            'obligations/ofertavehiculo',
            'customers/information',
            'customers/update',
            'obligations/index',
            'obligations/evaluar',
            'obligations/oferta',
            'obligations/resumen',
            'obligations/normalizar',
            'obligations/evaluar_oferta',
            'obligations/generate_pdf',
            'obligations/finalizar',
            'obligations/export',
            'obligations/import',
            'obligations/nuevasCuotas',
            'obligations/nuevascuotas',
            'obligations/nuevas-cuotas',
            'obligations/send_committee',
            'obligations/reject_offer',
            'cityoffices/oficinas_ciudad',
            'historycustomers/view',
            'historycustomers/history',
            'historycustomers/entregadocumentos',
            'historycustomers/desistenegociacion',

            'asesor/index',
            'asesor/view',
            'asesor/edit',
            'asesor/add',
            'committees/index',
            'committees/view',

            'tickets/delete',
            'tickets/index',
            'tickets/view',
            'tickets/add',
            'tickets/solve',
            'tickets/developing',
            'tickets/approved',

            'obligations/reportarerror',
            'obligations/resultado',

            'trainings/index',
            'trainings/view',
            'trainings/viewresorce',
        );

        $coordinador = array(
            'customers/consult',
            'obligations/pagototalvehiculo',
            'obligations/validarofertavehiculo',
            'obligations/ofertavehiculo',
            'customers/information',
            'customers/update',
            'obligations/index',
            'obligations/evaluar',
            'obligations/oferta',
            'obligations/resumen',
            'obligations/normalizar',
            'obligations/evaluar_oferta',
            'obligations/generate_pdf',
            'obligations/finalizar',
            'obligations/export',
            'obligations/import',
            'obligations/nuevasCuotas',
            'obligations/nuevascuotas',
            'obligations/nuevas-cuotas',
            'obligations/send_committee',
            'obligations/reject_offer',
            'cityoffices/oficinas_ciudad',
            'historycustomers/view',
            'historycustomers/history',

            'asesor/index',
            'asesor/view',
            'asesor/edit',
            'asesor/add',
            'committees/index',
            'committees/view',
            'committees/aceptar_comite',
            'committees/rechazar_comite',

            'tickets/delete',
            'tickets/index',
            'tickets/view',
            'tickets/add',
            'obligations/reportarerror',
            'obligations/resultado',

            'trainings/index',
            'trainings/view',
            'trainings/viewresorce',

            'historycustomers/entregadocumentos',
            'historycustomers/desistenegociacion',
        );


        $cur_page = strtolower($this->request->getParam('controller') . '/' . strtolower($this->request->getParam('action')));

        switch ($this->Auth->user('role_id')) {
            case RolesTable::Admin: // Admin
                $allows = array_merge($allowAll, $allowAdmin);
                if (in_array($cur_page, $allows) && strtolower($this->request->getParam('prefix')) == 'admin'){
                    return true;
                }else{
                    return false;
                }
                break;
            case RolesTable::Asesor: // Consultant
                $allows = array_merge($allowAll, $allowAsesor);
                if (in_array($cur_page, $allows) && strtolower($this->request->getParam('prefix')) == 'asesor'){
                    return true;
                }else{
                    return false;
                }
                break;
            case RolesTable::Coordinador: // Consultant
                $allows = array_merge($allowAll, $coordinador);
                if (in_array($cur_page, $allows) && strtolower($this->request->getParam('prefix')) == 'coordinador'){
                    return true;
                }else{
                    return false;
                }
                break;
            case RolesTable::Super: // Consultant
                $allows = array_merge($allowAll, $allowSuper);
                if (in_array($cur_page, $allows) && strtolower($this->request->getParam('prefix')) == 'super'){
                    return true;
                }else{
                    return false;
                }
                break;
            default:
                if (in_array($cur_page, $allowAll)){
                    return true;
                }else{
                    echo $cur_page;
                    exit;
                    return false;
                }
        }
        return false;
    }

    /**
     * @param $typeActivity
     * @param $modelId
     * @param null $description
     * @param null $model
     */
    public function saveLogActivity($typeActivity, $modelId, $description = null, $model = null){
        $this->loadModel('AccessActivities');
        $access = $this->AccessActivities->newEntity();
        $data = [
            'model'             => ($model == null)?$this->modelClass:$model,
            'model_id'          => $modelId,
            'date'              => date('Y-m-d H:i:s'),
            'access_log_id'     => $this->Auth->user('session_id'),
            'type_activity_id'  => $typeActivity,
            'description'       => ($description == null)?'':$description
        ];
        $access = $this->AccessActivities->patchEntity($access, $data);
        $this->AccessActivities->save($access);
    }

    /**
     * FetchSettings method
     *
     * @return \Cake\Network\Response|null|void
     */
    private function fetchSettings(){
        $this->loadModel('Settings');
        $keys = $this->Settings->getKeyValuePairs();
        return $keys;
    }

    /**
     * FetchSettings method
     *
     * @return \Cake\Network\Response|null|void
     */
    private function fetchConditios(){
        $this->loadModel('Conditions');
        $keys = $this->Conditions->getKeyValuePairs();
        return $keys;
    }

    public function milSuperior($valor){

        return (ceil(ceil($valor)/1000))*1000;

    }

    public function tiempos($inicio,$fin,$accion){
        $demora = $fin-$inicio;
        $demora = round($demora,2);
        $business = $this->Auth->user('busines.name');
        $user = $this->Auth->user('name');

        \Cake\Log\Log::write('emergency', $inicio.', '.$fin.', '.$demora.', '.$accion.', '.$business.', '.$user, ['tiempo']);

    }

    public function update_password($id){


        if($this->Auth->user('id') != $id){
            $this->Flash->error(__('Error validación usuario'));
            return $this->redirect('/'.strtolower($this->Auth->user('role.prefix')));
        }

        $user = $this->Users->get($id, [
            'contain' => ['Attachments', 'Roles']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            if(!empty($this->request->data['password_update'])){
                $this->request->data['token'] = $this->request->data['password_update'];
                $this->request->data['token_visible'] = null;
            }else {
                unset($this->request->data['password_update']);
                unset($this->request->data['password_confirm_update']);
            }

            $user = $this->Users->patchEntity($user, $this->request->getData());

            $date = date('Y-m-d');
            $newDate = strtotime ( '+60 day' , strtotime ( $date ));
            $newDate = date ( 'Y-m-d' , $newDate );
            $user->password_expiration_date = $newDate;

            if ($this->Users->save($user)) {

                $authUser = $this->Users->get($id, [
                    'contain' => [
                        'Roles' => [
                            'fields' => [
                                'Roles.name',
                                'Roles.prefix'
                            ]
                        ],
                        'AccessGroups' => [
                            'fields' => [
                                'AccessGroups.name',
                                'AccessGroups.all_ips'
                            ]
                        ],
                        'Attachments'
                    ]
                ])->toArray();

                $this->Auth->setUser($authUser);

                $this->Flash->success(__('The password has been saved.'));
                return $this->redirect('/'.strtolower($this->Auth->user('role.prefix')));
            } else {
                $this->Flash->error(__('The Profile could not be saved. Please, try again.'));
                #return $this->redirect('/'.strtolower($this->Auth->user('role.prefix')));
            }
        }

        $this->viewBuilder()->setTemplatePath('/Users');
        $this->set(compact('user'));
    }

    public function Log($data, $customer = null, $adjusted = null)
    {
        $this->loadModel('Logs');
        $timeInicio = microtime(true);
        $log = $this->Logs->newEntity();
        $data['user_id'] = $this->Auth->user('id');
        $log = $this->Logs->patchEntity($log, $data);
        $save =$this->Logs->save($log);
        $timeFinal = microtime(true);
        $duracion = ($timeFinal) - ($timeInicio);
        //validación del log update
        $mensaje = "Función LOG";
        $parametros = [
            'Data' => $data,
            'Log' => $log,
            'SaveLOG' => $save
        ];
        $logTran = LogTransaccionController::EscribirLogHistory($mensaje,$parametros);
        if ($save) {
            Cache::write($this->Auth->user('session_id') . 'log', $log->id);
            $logTran = LogTransaccionController::EscribirTiempo('Consulta','InsertLog','AppController','Log',$log->id,$duracion);
        } else {
            $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Log'));
            throw new Exception("No fue posible crear un nuevo log");
        }
    }
    
    public function createHistory($obligaciones, $customer, $state = HistoryStatusesTable::CONSULTA,$alternative = 'No'){

        $this->loadModel('HistoryCustomers');
        $detalle = [];
        $datosNormalizacion = [];
        $datosCastigada = [];

        /*CAMPOS NUEVOS*/
        $consecutivo_cliente = $customer->sequential_customer;
        $consecutivo_obligacion = null;
        /*CAMPOS NUEVOS*/

        foreach ($obligaciones as $obligacion) {
            /** @var  $obligacion Obligation*/

            /*CAMPOS NUEVOS*/
            $consecutivo_obligacion = $obligacion->sequential_obligation;
            /*CAMPOS NUEVOS*/

            if ($alternative == 'Si') {
                $strategy = $obligacion->estrategias[$obligacion->estrategia];
            } else {
                $strategy = 'sin estrategia';
            }
            $detalle[] = [
                'obligation' => $obligacion->obligation,
                'type_obligation_id' => $obligacion->type_obligation_id,
                'strategy' => $strategy,
                'type_strategy' => 0,
                'term' => (int)0,
                'new_fee' => 0,
                'selected' => 0,
                'total_debt' => $obligacion->total_debt,
                'fee' => $obligacion->fee,
                'minimum_payment' => $obligacion->minimum_payment,
                'rate_ea' => $obligacion->rate,
                'rate_em' => $obligacion->tasaMensual,
                'days_past_due' => $obligacion->days_past_due,
                'payment_agreed' => $obligacion->pagoSugerido,
                'pago_real' => $obligacion->pagoReal,
                'sequential_obligation' => $consecutivo_obligacion /*CAMPOS NUEVOS*/
            ];

        }

        if ($normalizacion = Cache::read($this->Auth->user('session_id') . 'normalizacion')) {

            foreach ($normalizacion['data'] as $key => $item) {
                $selected = 0;
                if ($key == $this->request->getData('propuesta_aceptada')) {
                    $selected = 1;
                }

                $datosNormalizacion[] = [
                    'fee' => $item['cuota'],
                    'rate' => $item['tasa'],
                    'term' => $item['plazo'],
                    'selected' => $selected,
                ];
            }
        }

        if ($propuestaCastigada = Cache::read($this->Auth->user('session_id') . 'propuesta_castigada')) {
            
            foreach ($propuestaCastigada as $key => $item) {
                $selected = 0;
                if ($key == $this->request->getData('propuesta_aceptada_castigada')) {
                    $selected = 1;
                }
                
                $datosCastigada[] = [
                    'fee' => $item['cuota'],
                    'rate' => $item['tasa_anual'],
                    'term' => $item['plazo'],
                    'selected' => $selected,
                    'initial_condonation' => $item['condonacion_inicial'],
                    'value_initial_condonation' => $item['valor_condonacion_inicial'],
                    'end_condonation' => $item['condonacion'],
                    'value_end_condonation' => $item['valor_condonacion'],
                    'initial_payment' => $item['pago_inicial'],
                ];
            }
        }

        $paymentAgreed = str_replace('.','',$this->request->getData('payment_agreed'));

        $history = $this->HistoryCustomers->newEntity();
        /** @var  $customer Customer*/
        $data = [
            'type_identification_id' => $customer->customer_type_identification_id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'income' => $customer->income,
            'payment_capacity' => $customer->payment_capacity,
            'initial_payment_punished' => $customer->initial_payment_punished,
            'payment_agreed' => $paymentAgreed,
            'history_status_id' => $state,
            'user_id' => $this->Auth->user('id'),
            'alternative' => $alternative,
            'log_id' => Cache::read($this->Auth->user('session_id') . 'log'),
            'income_source' => $customer->income_source,
            'history_details' => $detalle,
            'history_normalizations' => $datosNormalizacion,
            'history_punished_portfolios' => $datosCastigada,
            'payment_hp' => $customer->payment_punished_hp,
            'cnd' => $customer->cnd,
            'legal_process' => $customer->legalProcess,
            'customer_observations' => $customer->observations,
            'customer_alternatives' => $customer->alternatives,
            'retrenched_policy' => $obligacion->retrenched_policy,
            'sequential_customer' => $consecutivo_cliente /*CAMPOS NUEVOS*/
        ];

        $timeInicio = microtime(true);
        $history = $this->HistoryCustomers->patchEntity($history, $data);
        $result = $this->HistoryCustomers->save($history);
        if ($result == null){
            throw new Exception("No fue posible guardar el history en la función de crear history");
        }
        $timeFinal = microtime(true);
        $duracion = ($timeFinal) - ($timeInicio);
        $logId = Cache::read($this->Auth->user('session_id') . 'log');
        try {
            if($result){
                if($state == HistoryStatusesTable::CONSULTA || $state == HistoryStatusesTable::PENDIENTE){
                    $logTransactional = LogTransactional::PersistenceLogsTransactional($result);
                    $logTran = LogTransaccionController::EscribirTiempo('Consulta','InsertHistory','AppController','createHistory',$logId,$duracion);
                    Cache::write($this->Auth->user('session_id').'-'.$customer->id.'-history',$result->id);
                }
                return $result->id;
            }else{
                \Cake\Log\Log::alert("LLEGA LLEGA LLEGA LLEGA LLEGA LLEGA LLEGA LLEGA");
                \Cake\Log\Log::alert($data);
                \Cake\Log\Log::warning( $history->getErrors(), 'historial');
                return false;
            }
        } catch (\Throwable $th) {
            
            throw $th;
        }
        
        
    }

    public function updateHistory($customer, $state = HistoryStatusesTable::PENDIENTE, $alternative = 'Si', $normalizacion = null){
        
        $this->loadModel('HistoryCustomers');
        $this->loadModel('HistoryDetails');
        $this->loadModel('HistoryNormalizations');
        $this->loadModel('HistoryPunishedPortfolios');

        $obligaciones = Cache::read($this->Auth->user('session_id').'obligaciones');
        $idHistory = Cache::read($this->Auth->user('session_id').'-'.$this->customer->id.'-history');
        $reasonRejection = $this->request->getData('reason_rejection');
        //$paymentAgreed = str_replace('.','',$this->request->getData('payment_agreed'));
        //$paymentAgreedUnificacion = str_replace('.','',$this->request->getData('payment_agreed_unificacion'));

        $paymentAgreed = ceil($this->request->getData('payment_agreed'));
        $paymentAgreedUnificacion = ceil($this->request->getData('payment_agreed_unificacion'));

        $pagoAcumuladoResumen = ((int) $paymentAgreed + (int) $paymentAgreedUnificacion);
        /*var_dump($this->request->getData());
        die("<pre>".print_r($pagoAcumuladoResumen)."</pre>");*/


        
        if ($state == HistoryStatusesTable::RECHAZADA && $reasonRejection == null){
            $reasonRejection = 'No existe ninguna oferta que se ajuste a la capacidad de pago';
        }
        
        $detalle = [];
        $datosNormalizacion = [];
        $datosCastigada = [];

        /*CAMPOS NUEVOS*/
        $consecutivo_cliente = $customer->sequential_customer;
        $consecutivo_obligacion = null;
        /*CAMPOS NUEVOS*/

        if ($idHistory == null || $idHistory == 0 || $idHistory < 0) {
            throw new Exception("Se genera excepcion ya que el id history viene null o 0.");
        }

        if($state == HistoryStatusesTable::PENDIENTE || $state == HistoryStatusesTable::ACEPTADA || $state == HistoryStatusesTable::COMITE) {
            foreach ($obligaciones as $obligacion) {
                /** @var  $obligacion Obligation */
                
                /*CAMPOS NUEVOS*/
                $consecutivo_obligacion = $obligacion->sequential_obligation;
                /*CAMPOS NUEVOS*/

                if ($alternative == 'Si') {
                    $strategy = $obligacion->estrategias[$obligacion->estrategia];
                    
                    if ($obligacion->estrategia == 1) {
                        \Cake\Log\Log::debug("Unificacion");
                        if ($normalizacion == null){
                            \Cake\Log\Log::alert("No se encontro la normalización");
                            throw new Exception(
                                "Se genero error al momento de realizar una lectura de la data de normalización controller App"
                            );
                        }
                        if($obligacion->pagare === 0 || $obligacion->pagare === null){
                            \Cake\Log\Log::debug("Sin Pagare");
                        } 
                    }
                    if ($obligacion->estrategia == 12) {
                        \Cake\Log\Log::debug("ACPK");
                        if($obligacion->pagare === 0 || $obligacion->pagare === null){
                            \Cake\Log\Log::debug("Sin Pagare");
                           $necesitaPagareACPK = 0;
                        }
                    }
                } else {
                    $strategy = 'sin estrategia';
                }
                $detalle[] = [
                    'obligation' => $obligacion->obligation,
                    'type_obligation_id' => $obligacion->type_obligation_id,
                    'strategy' => $strategy,
                    'type_strategy' => $obligacion->estrategia,
                    'term' => (int)$obligacion->nuevoPlazo,
                    'new_fee' => $obligacion->nuevaCuota,
                    'selected' => $obligacion->negociable,
                    'total_debt' => $obligacion->total_debt,
                    'fee' => $obligacion->fee,
                    'minimum_payment' => $obligacion->minimum_payment,
                    'rate_ea' => $obligacion->rate,
                    'rate_em' => $obligacion->tasaMensual,
                    'days_past_due' => $obligacion->days_past_due,
                    'payment_agreed' => $obligacion->pagoSugerido,
                    'pago_real' => $obligacion->pagoReal,
                    'currency' => $obligacion->currency,
                    'retrenched_policy' => $obligacion->retrenched_policy,
                    'sequential_obligation' => $consecutivo_obligacion, /*CAMPOS NUEVOS*/
                    'pagare' => $obligacion->pagare
                ];

            }
            $idLogL = Cache::read($this->Auth->user('session_id') . 'log');
            if (!empty($normalizacion) || $normalizacion != null) {
                $timeInicioContenoNormalizacion = microtime(true);
                $HistoryNormalizationsGetCount = $this->HistoryNormalizations->find()->where(['history_customer_id' => $idHistory])->count();
                $timeFinalContenoNormalizacion = microtime(true);
                $duracion = ($timeFinalContenoNormalizacion) - ($timeInicioContenoNormalizacion);
                $logTran = LogTransaccionController::EscribirTiempo('Declinada','Conteo Normalizacion','AppController','updateHistory',$idLogL,$duracion);
                if ($HistoryNormalizationsGetCount > 0){
                    $timeInicioDeleteNormalizacion = microtime(true);
                    $HistoryNormalizationsDelete = $this->HistoryNormalizations->deleteAll(
                        ['history_customer_id' => $idHistory]
                    );
                    $timeFinalDeleteNormalizacion = microtime(true);
                    $duracion = ($timeFinalDeleteNormalizacion) - ($timeInicioDeleteNormalizacion);
                    $logTran = LogTransaccionController::EscribirTiempo('Declinada','Eliminar Normalizacion','AppController','updateHistory',$idLogL,$duracion);
                    if ($HistoryNormalizationsDelete == null || $HistoryNormalizationsDelete == 0 || $HistoryNormalizationsDelete < 0) {
                        throw new Exception(
                            "Se genero error al momento de realizar transacción de la informacion a la tabla HistoryNormalizations (D) en la funcion updateHistory controller App"
                        );
                    }    
                }
                if ($normalizacion['data']){
                    foreach ($normalizacion['data'] as $key => $item) {
                        $selected = 0;
                        if ($key == $this->request->getData('propuesta_aceptada')) {
                            $selected = 1;
                        }
    
                        $datosNormalizacion[] = [
                            'fee' => $item['cuota'],
                            'rate' => $item['tasa'],
                            'term' => $item['plazo'],
                            'selected' => $selected,
                        ];
                    }
                }else{
                    
                    $datosNormalizacion[] = [
                        'fee' => $normalizacion['cuota'],
                        'rate' => $normalizacion['tasa'],
                        'term' => $normalizacion['plazo'],
                        'selected' => 1,
                    ];
                }
                
            }

            if ($propuestaCastigada = Cache::read($this->Auth->user('session_id') . 'propuesta_castigada')) {
                $timeInicioContenoCastigada = microtime(true);
                $HistoryPunishedPortfoliosGetCount = $this->HistoryPunishedPortfolios->find()->where(['history_customer_id' => $idHistory])->count();
                $timeFinalContenoCastigada = microtime(true);
                $duracion = ($timeFinalContenoCastigada) - ($timeInicioContenoCastigada);
                $logTran = LogTransaccionController::EscribirTiempo('Declinada','Conteo PunishedPortfolios','AppController','updateHistory',$idLogL,$duracion);
                if ($HistoryPunishedPortfoliosGetCount > 0){
                    $timeInicioDeleteCastigada = microtime(true);

                    $HistoryPunishedPortfoliosDelete = $this->HistoryPunishedPortfolios->deleteAll(
                        ['history_customer_id' => $idHistory]
                    );

                    $timeFinalDeleteCastigada = microtime(true);
                    $duracion = ($timeFinalDeleteCastigada) - ($timeInicioDeleteCastigada);
                    $logTran = LogTransaccionController::EscribirTiempo('Declinada','Eliminacion PunishedPortfolios','AppController','updateHistory',$idLogL,$duracion);

                    if ($HistoryPunishedPortfoliosDelete == null || $HistoryPunishedPortfoliosDelete == 0 || $HistoryPunishedPortfoliosDelete < 0) {
                        throw new Exception(
                            "Se genero error al momento de realizar transacción de la informacion a la tabla HistoryPunishedPortfolios (D) en la funcion updateHistory controller App."
                        );
                    }
                }

                foreach ($propuestaCastigada as $key => $item) {
                    $selected = 0;
                    if ($key == $this->request->getData('propuesta_aceptada_castigada')) {
                        $selected = 1;
                    }
                    $datosCastigada[] = [
                        'fee' => $item['cuota'],
                        'rate' => $item['tasa_anual'],
                        'term' => $item['plazo'],
                        'selected' => $selected,
                        'initial_condonation' => $item['condonacion_inicial'],
                        'value_initial_condonation' => $item['valor_condonacion_inicial'],
                        'end_condonation' => $item['condonacion'],
                        'value_end_condonation' => $item['valor_condonacion'],
                        'initial_payment' => $item['pago_inicial'],
                    ];
                }
            }
            $timeInicioDelete = microtime(true);
            $HistoryDetailsDelete = $this->HistoryDetails->deleteAll(
                ['history_customer_id' => $idHistory]
            );
            $timeFinalDelete = microtime(true);
            $duracion = ($timeFinalDelete) - ($timeInicioDelete);
            $logTran = LogTransaccionController::EscribirTiempo('Declinada','EliminarHistoryDetails','AppController','updateHistory',$idLogL,$duracion);
            if ($HistoryDetailsDelete == null || $HistoryDetailsDelete == 0 || $HistoryDetailsDelete < 0) {
                throw new Exception(
                    "Se genero error al momento de realizar transacción de la informacion a la tabla HistoryDetails (D) en la funcion updateHistory controller App."
                );
            }
        }
        
        $infoHistory = [
            'history_status_id' => $state,
            'reason_rejection' => $reasonRejection,
            'income' => $customer->income,
            'payment_capacity' => $customer->payment_capacity,
            'initial_payment_punished' => $customer->initial_payment_punished,
            'payment_hp' => $customer->payment_punished_hp,
            'payment_agreed' => $pagoAcumuladoResumen,
            'income_source' => $customer->income_source,
            'alternative' => $alternative,
            'history_details' => $detalle,
            'history_normalizations' => $datosNormalizacion,
            'history_punished_portfolios' => $datosCastigada,
            'sequential_customer' => $consecutivo_cliente /*CAMPOS NUEVOS*/
        ];

        $history = $this->HistoryCustomers->get($idHistory,
            [
                'contain'=>[
                    'HistoryDetails',
                    'HistoryNormalizations',
                    'HistoryPunishedPortfolios',
                ]
            ]
        );

        $timeInicioDeleteCastigada = microtime(true);
        //history para validar información
        $informa = $history;
        $history = $this->HistoryCustomers->patchEntity($history,$infoHistory);

        $HistoryCustomersUpdate = $this->HistoryCustomers->save($history);

        $timeFinalDeleteCastigada = microtime(true);
        $duracion = ($timeFinalDeleteCastigada) - ($timeInicioDeleteCastigada);
        $logTran = LogTransaccionController::EscribirTiempo('Declinada','Guardar History','AppController','updateHistory',$idLogL,$duracion);
        //validación del history update
        $mensaje = "Función history update";
        $idLogL = Cache::read($this->Auth->user('session_id') . 'log');
        $customerL = Cache::read($this->Auth->user('session_id').'customer');
        $parametros = [
            'idLog' => $idLogL,
            'customer' => $customerL->id,
            'cargue' => $customerL->charge_id,
            'capacidad_pago' => $customerL->payment_capacity,
            'infoHistory' => $infoHistory,
            'History' => $informa,
            'History_patchEntity' => $history,
            'HistorySave' => $HistoryCustomersUpdate
        ];
        $logTran = LogTransaccionController::EscribirLogHistory($mensaje,$parametros);
        
        if ($HistoryCustomersUpdate == null || $HistoryCustomersUpdate == 0 || $HistoryCustomersUpdate < 0) {
            throw new Exception(
                "Se genero error al momento de realizar transacción de la informacion a la tabla HistoryCustomers (U) en la funcion updateHistory controller App.".json_encode($infoHistory)
            );
        }
        
        $logTransactional = LogTransactional::PersistenceLogsTransactional($history);

        if($state == HistoryStatusesTable::RECHAZADA || $state == HistoryStatusesTable::ACEPTADA || $state == HistoryStatusesTable::COMITE){
            if($state == HistoryStatusesTable::ACEPTADA){
                try {
                    $logCommercial = LogCommercial::PersistenceLogsCommercial($history);
                    $logRediferido = LogRediferido::PersistenceLogsRediferido($history);
                } catch (\Throwable $th) {
                    throw new Exception("Error persistiendo log commercial o rediferido", 1, $th);                        
                }
            }
            Cache::delete($this->Auth->user('session_id').'-'.$this->customer->id.'-history');
        }
        return $idHistory;
    }

    public function create_history($state = HistoryStatusesTable::PENDIENTE, $alternative = 'Si',$obligaciones){
        
        $this->Flash->error('Create_History');
        $this->loadModel('HistoryCustomers');
        $obligaciones = [];
        if(Cache::read($this->Auth->user('session_id').'-'.$this->customer->id.'-history') && $state != HistoryStatusesTable::PENDIENTE){
            $reasonRejection = $this->request->getData('reason_rejection');
            $this->HistoryCustomers->updateAll(
                [
                    'history_status_id' => $state,
                    'reason_rejection' => $reasonRejection,
                ],
                [
                    'id' => $idHistory
                ]
            );

            if($normalizacion = Cache::read($this->Auth->user('session_id').'normalizacion') && $state != HistoryStatusesTable::RECHAZADA){
                $this->loadModel('HistoryNormalizations');

                $normalizacion = Cache::read($this->Auth->user('session_id').'normalizacion');


                $this->HistoryNormalizations->deleteAll(
                    ['history_customer_id' => $idHistory]
                );

                $historyNormalizations = $this->HistoryNormalizations->newEntity();
                $datos = [];
                foreach($normalizacion['data'] as $key => $item){
                    $selected = 0;
                    if($key == $this->request->getData('propuesta_aceptada')){
                        $selected = 1;
                    }

                    $datos[] = [
                        'fee' => $item['cuota'],
                        'rate' => $item['tasa'],
                        'term' => $item['plazo'],
                        'selected' => $selected,
                        'history_customer_id' => $idHistory,
                    ];
                }

                $data['history_normalizations'] = $datos;

                $historyNormalizations = TableRegistry::get('HistoryNormalizations');
                $entities = $historyNormalizations->newEntities($datos);
                $result = $historyNormalizations->saveMany($entities);

            }

            if($propuestaCastigada = Cache::read($this->Auth->user('session_id').'propuesta_castigada')){
                $this->loadModel('HistoryPunishedPortfolios');
                $this->HistoryPunishedPortfolios->deleteAll(
                    ['history_customer_id' => $idHistory]
                );

                $propuestaCastigada = Cache::read($this->Auth->user('session_id').'propuesta_castigada');

                $datos = [];
                foreach($propuestaCastigada as $key => $item){
                    $selected = 0;
                    if($key == $this->request->getData('propuesta_aceptada_castigada')){
                        $selected = 1;
                    }
                   
                    $datos[] = [
                        'fee' => $item['cuota'],
                        'rate' => $item['tasa_anual'],
                        'term' => $item['plazo'],
                        'selected' => $selected,
                        'initial_condonation' => $item['condonacion_inicial'],
                        'value_initial_condonation' => $item['valor_condonacion_inicial'],
                        'end_condonation' => $item['condonacion'],
                        'value_end_condonation' => $item['valor_condonacion'],
                        'initial_payment' => $item['pago_inicial'],
                        'history_customer_id' => $idHistory
                    ];
                }
                $historyPunishedPortfolios = TableRegistry::get('HistoryPunishedPortfolios');
                $entities = $historyPunishedPortfolios->newEntities($datos);
                $result = $historyPunishedPortfolios->saveMany($entities);
            }
            Cache::delete($this->Auth->user('session_id').'-'.$this->customer->id.'-history');
            return $idHistory;
        }else{
            foreach ($this->obligaciones as $obligacion) {
                /** @var  $obligacion Obligation*/
                if($alternative == 'Si') {
                    $strategy = $obligacion->estrategias[$obligacion->estrategia];
                }else{
                    $strategy = 'sin estrategia';
                }

                $obligaciones[] = [
                    'obligation' => $obligacion->obligation,
                    'type_obligation_id' => $obligacion->type_obligation_id,
                    'strategy' => $strategy,
                    'type_strategy' => $obligacion->estrategia,
                    'term' => (int)$obligacion->nuevoPlazo,
                    'new_fee' => $obligacion->nuevaCuota,
                    'selected' => $obligacion->negociable,
                    'total_debt' => $obligacion->total_debt,
                    'fee' => $obligacion->fee,
                    'minimum_payment' => $obligacion->minimum_payment,
                    'rate_ea' => $obligacion->rate,
                    'rate_em' => $obligacion->tasaMensual,
                    'days_past_due' => $obligacion->days_past_due,
                    'currency' => $obligacion->currency
                ];
            }

            $paymentAgreed = str_replace('.','',$this->request->getData('payment_agreed'));

            $history = $this->HistoryCustomers->newEntity();

            $data = [
                'type_identification_id' => $this->customer->customer_type_identification_id,
                'customer_id' => $this->customer->id,
                'customer_name' => $this->customer->name,
                'customer_email' => $this->customer->email,
                'income' => $this->customer->income,
                'payment_capacity' => $this->customer->payment_capacity,
                'initial_payment_punished' => $this->customer->initial_payment_punished,
                'payment_agreed' => $paymentAgreed,
                'history_status_id' => $state,
                'history_details' => $obligaciones,
                'user_id' => $this->Auth->user('id'),
                'alternative' => $alternative,
                'office_name' => $this->request->getData('oficina'),
                'documentation_date' => $this->request->getData('fecha'),
                'log_id' => Cache::read($this->Auth->user('session_id') . 'log'),
                'income_source' => $this->customer->income_source,
            ];



            if($normalizacion = Cache::read($this->Auth->user('session_id').'normalizacion')){

                $datos = [];
                foreach($normalizacion['data'] as $key => $item){
                    $selected = 0;
                    if($key == $this->request->getData('propuesta_aceptada')){
                        $selected = 1;
                    }

                    $datos[] = [
                        'fee' => $item['cuota'],
                        'rate' => $item['tasa'],
                        'term' => $item['plazo'],
                        'selected' => $selected,
                    ];
                }

                $data['history_normalizations'] = $datos;
            }
            
            if($propuestaCastigada = Cache::read($this->Auth->user('session_id').'propuesta_castigada')){
                $datos = [];
                foreach($propuestaCastigada as $key => $item){
                    $selected = 0;
                    if($key == $this->request->getData('propuesta_aceptada_castigada')){
                        $selected = 1;
                    }

                    $datos[] = [
                        'fee' => $item['cuota'],
                        'rate' => $item['tasa_anual'],
                        'term' => $item['plazo'],
                        'selected' => $selected,
                        'initial_condonation' => $item['condonacion_inicial'],
                        'value_initial_condonation' => $item['valor_condonacion_inicial'],
                        'end_condonation' => $item['condonacion'],
                        'value_end_condonation' => $item['valor_condonacion'],
                        'initial_payment' => $item['pago_inicial'],
                    ];
                }

                $data['history_punished_portfolios'] = $datos;
            }

            $history = $this->HistoryCustomers->patchEntity($history, $data);
            if($result = $this->HistoryCustomers->save($history)){
                if($state == HistoryStatusesTable::PENDIENTE){
                    Cache::write($this->Auth->user('session_id').'-'.$this->customer->id.'-history',$result->id);
                }elseif(Cache::read($this->Auth->user('session_id').'-'.$this->customer->id.'-history')){
                    Cache::delete($this->Auth->user('session_id').'-'.$this->customer->id.'-history');
                }
                return $result->id;
            }else{
                return false;
            }

        }

    }

    

    /**
     * @param $condiciones
     * @param $comparar
     * @return null|string
     */
    public function getValorCondicion($condiciones, $comparar){

        $valor = null;
        /** @var  $condicion Condition*/
        if(!empty($condiciones)){
            foreach ($condiciones as $index => $condicion) {
                if ($condicion->operator == ConditionsTable::IGUAL){
                    if($comparar == $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MAYOR){
                    if($comparar > $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MAYORIGUAL){
                    if($comparar >= $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MENOR){
                    if($comparar < $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MENORIGUAL){
                    if($comparar <= $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::BETWEEN){
                    $valores = explode(',',$condicion->compare);
                    if(is_array($valores)){
                        if($comparar >= $valores[0] && $comparar<=$valores[1]){
                            $valor = $condicion->value;
                            break;
                        }
                    }
                }
            }

        }
        return $valor;

    }

    /**
     * @property $Rejected
     */
    // public function addNovelty($type = 0, $detail = '') {
    //     $data = [
    //         'type_rejected_id' => $type,
    //         'customer_identification' => $this->customer->id,
    //         'history_customer_id' => Cache::read($this->Auth->user('session_id').'-'.$this->customer->id.'-history'),
    //         'customer_type_identification_id' => $this->customer->customer_type_identification_id,
    //         'user_id' => $this->Auth->user('id'),
    //         'details' => $detail
    //     ];
    //     $rejected= TableRegistry::get('Rejected');
    //     $reject = $rejected->newEntity();
    //     $reject = $rejected->patchEntity($reject,$data);

    //     if(!$rejected->save($reject)){
    //         log::alert(pr($reject->getErrors()));
    //     }
    // }
}
