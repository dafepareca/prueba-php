<?php
namespace App\Controller\Asesor;

use App\Controller\AppController;
use App\Model\Entity\User;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Network\Exception\NotFoundException;
use Cake\Auth\DefaultPasswordHasher;
use App\Model\Table\AccessTypesActivitiesTable;
use App\Model\Table\UserStatusesTable;
use Cake\Validation\Validation;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\AccessLogsTable $AccessLogs
 * @property \App\Controller\Component\SMSComponent $SMS
 * @property \App\Model\Table\OfficesTable $Offices
 */
class UsersController extends AppController{


    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->loadModel('AccessLogs');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'getToken', 'logout', 'autocomplete', 'profile','test']);
    }

    /**
     * login method
     *
     * @return \Cake\Network\Response|null Redirects to index.
     */

    public function login(){
        if($this->request->is('post')){

            if (!Validation::email($this->request->getData('email'))) {
                $this->Auth->setConfig('authenticate', [
                    'Form' => [
                        'fields' => ['username' => 'identification']
                    ]
                ]);
                $this->Auth->constructAuthenticate();
                $this->request->data['identification'] = $this->request->data['email'];
                unset($this->request->data['email']);
            }

            $user = $this->Auth->identify();
            if($user){
                $accessLog = $this->AccessLogs->newEntity();
                $data = [
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'date_login' => date('Y-m-d H:i:s'),
                    'user_id' => $user['id']
                ];
                $accessLog = $this->AccessLogs->patchEntity($accessLog, $data);
                $accessLog = $this->AccessLogs->save($accessLog);
                $user['session_id'] =  $accessLog->id;

                if(!$this->validateIp($user)){
                    $this->Flash->error(__('Access denied by location'));
                    $this->AccessLogs->updateAll(['logout_type_id' => 3, 'date_logout' => date('Y-m-d H:i:s')], ['id' => $accessLog->id]);
                    return $this->redirect($this->Auth->logout());
                }

                $this->Auth->setUser($user);
                $this->Users->updateAll(['last_login' => date('Y-m-d H:i:s')], ['id' => $this->Auth->user('id')]);
                return $this->redirect('/'.strtolower($user['role']['prefix']));

            }else{
                $this->Flash->error(__('Invalid email or token'), ['key' => 'auth']);
            }
        }
        $this->viewBuilder()->setLayout('login');
    }

    public function logout(){
        $this->AccessLogs->updateAll(['logout_type_id' => 2, 'date_logout' => date('Y-m-d H:i:s')], ['id' => $this->Auth->user('session_id')]);
        return $this->redirect($this->Auth->logout());
    }

    public function getToken(){
        if($this->request->is('post')){
            $result = $this->Users->find()
                ->select(['email', 'id', 'mobile'])
                ->where ([
                    'email' => $this->request->data['email'],
                    'Users.user_status_id' => UserStatusesTable::Active
                ]);
            if($result->count() > 0){
                $this->loadComponent('SMS');
                $user = $result->first();
                $token = strtoupper(substr(hash("sha512",rand(10000, 99999)),1, 6));
                $this->Users->updateAll(
                    [
                        'token' => (new DefaultPasswordHasher)->hash($token),
                        'token_visible' => $token
                    ],
                    ['id' => $user->id]
                );
                if (Configure::read('debug')) {
                    $this->Flash->success(__('Token sent to registered mobile').' ('.$token.')', ['key' => 'auth']);
                }else {
                    $this->SMS->sendSMS($user->mobile, 'Token de Analytics ('.$user->email.'): '.$token);
                    $this->Flash->success(__('Token sent to registered mobile'), ['key' => 'auth']);
                }
                return $this->redirect(['action' => 'login']);
            }else{
                $this->Flash->error(__('Invalid email. Please, try again.'), ['key' => 'auth']);
            }
        }
        $this->viewBuilder()->setLayout('login');
    }

    public function autocomplete()
    {
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $name = $this->request->getQuery('term');
            $results = $this->Users->find('all', [
                'conditions' => [
                    'name LIKE' => $name . '%'
                ]
            ]);
            $resultsArr = [];
            foreach ($results as $result) {
                $resultsArr[] = [
                    'label' => $result['name'],
                    'value' => $result['name']
                ];
            }
            echo json_encode($resultsArr);
        }
    }

    public function profile(){
        $userAuth = $this->Auth->user();
        $user = $this->Users->get($userAuth['id'], [
            'contain' => ['Attachments', 'Roles']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(empty($this->request->data['attachment']['photo']['tmp_name'])){
                unset($this->request->data['attachment']);
            }
            if(!empty($this->request->data['password_update'])){
                $this->request->data['token'] = $this->request->data['password_update'];
                $this->request->data['token_visible'] = null;
            }else {
                unset($this->request->data['password_update']);
                unset($this->request->data['password_confirm_update']);
            }
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $authUser = $this->Users->get($userAuth['id'], [
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
                // Log user in using Auth
                $this->Auth->setUser($authUser);
                $this->saveLogActivity(AccessTypesActivitiesTable::PROFILE, $userAuth['id'], 'Update profile');
                $this->Flash->success(__('The Profile has been saved.'));
                return $this->redirect('/'.strtolower($userAuth['role']['prefix']));
            } else {
                $this->Flash->error(__('The Profile could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }



    public function validateIp($user){

        if($user['access_group']['all_ips']){
            return true;
        }else{
            foreach($user['access_group']['ips_groups'] as $ips){
                if($ips['ip_address'] == $_SERVER['REMOTE_ADDR']){
                    return true;
                }
            }
        }
        return false;
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

        $this->set(compact('user'));
    }


    public function  test(){
        die();
     $this->autoRender = false;
        $this->loadModel('Offices');


        #$ciudades = [51,52,53,54,55,56,57,58,59,60];
        $ciudades = [];
        for($i=851;$i<=878;$i++){
            $ciudades[] = $i;
        }
        pr($ciudades);
        foreach($ciudades as $ciudad){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://daviviendaapp.com/puntos/getMarkers",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"cityId\"\r\n\r\n$ciudad\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"filters[]\"\r\n\r\nchk_oficina\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                    "postman-token: e054add2-f163-4ec1-89ae-669f8b8bfac5"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
                die('error'. $err);
            } else {
                $data = json_decode($response, true);
            }
            foreach($data as $oficina){
                if(!isset($datos[$oficina['Punto']['id']])){
                    $datos[$oficina['Punto']['id']] = [
                        'city_office_id' =>  $ciudad,
                        'name' =>  $oficina['Punto']['nombre'],
                        'address' =>  $oficina['Punto']['direccion'],
                    ];

                    if($oficina['HorariosTipo']['nombre'] == 'Horario normal' || $oficina['HorariosTipo']['nombre'] == 'Horario adicional'){
                        $datos[$oficina['Punto']['id']]['schedules'][]['schedule'] = $oficina['Dia1']['nombre'].' A '.$oficina['Dia2']['nombre']. ' de '.$oficina['Hora1']['nombre']. ' '.$oficina['Hora1']['meridiano']. ' a '.$oficina['Hora2']['nombre']. ' '.$oficina['Hora2']['meridiano'];
                    }elseif($oficina['HorariosTipo']['nombre'] == 'Sábados'){
                        $datos[$oficina['Punto']['id']]['schedules'][]['schedule'] = 'Sábados de '.$oficina['Hora1']['nombre']. ' '.$oficina['Hora1']['meridiano']. ' a '.$oficina['Hora2']['nombre']. ' '.$oficina['Hora2']['meridiano'];
                    }

                }else{

                    if($oficina['HorariosTipo']['nombre'] == 'Horario normal' || $oficina['HorariosTipo']['nombre'] == 'Horario adicional'){
                        $datos[$oficina['Punto']['id']]['schedules'][]['schedule'] = $oficina['Dia1']['nombre'].' A '.$oficina['Dia2']['nombre']. ' de '.$oficina['Hora1']['nombre']. ' '.$oficina['Hora1']['meridiano']. ' a '.$oficina['Hora2']['nombre']. ' '.$oficina['Hora2']['meridiano'];
                    }elseif($oficina['HorariosTipo']['nombre'] == 'Sábados'){
                        $datos[$oficina['Punto']['id']]['schedules'][]['schedule'] = 'Sábados de '.$oficina['Hora1']['nombre']. ' '.$oficina['Hora1']['meridiano']. ' a '.$oficina['Hora2']['nombre']. ' '.$oficina['Hora2']['meridiano'];
                    }

                }

            }

        }

        $err = [];
        foreach($datos as $office){

            $accord = $this->Offices->newEntity();
            $accord = $this->Offices->patchEntity($accord, $office);

            if($result = $this->Offices->save($accord)){

            }else{
               $err[] = $office;
            }


        }

        pr($err);

        pr(count($datos));
        pr($datos);


    }
}