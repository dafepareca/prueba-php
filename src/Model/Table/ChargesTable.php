<?php
namespace App\Model\Table;

use Cake\Cache\Cache;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;

/**
 * Charges Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\HasMany $Customers
 * @property \App\Model\Table\ObligationsTable|\Cake\ORM\Association\HasMany $Obligations
 *
 * @method \App\Model\Entity\Charge get($primaryKey, $options = [])
 * @method \App\Model\Entity\Charge newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Charge[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Charge|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Charge patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Charge[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Charge findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChargesTable extends Table
{

    const ELIMINADO = 1;
    const ACTIVO = 2;
    const INACTIVO = 3;
    const CARGANDO = 4;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $settings = Cache::read('settings', 'long');
        $port = $settings['FileRepository']['port'];
        $path = $settings['FileRepository']['path'];
        $server = $settings['FileRepository']['server'];
        $user = $settings['FileRepository']['user'];
        $password = $settings['FileRepository']['password'];
        $localActivated = (int) $settings['FileRepositoryLocal']['activate'];

        $filesystem = new Adapter([
            'host' => $server,
            'username' => $user,
            'password' => $password,
            'port' => $port
        ]);

        $this->setTable('charges');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $fileBehavior = [
            'fields' => [
                'dir' => 'file_dir'
            ],
            'nameCallback' => function (array $data, array $settings) {
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                return 'dav-'.date('YmdHms').'.'.$extension;
            },
            'keepFilesOnDelete' => false,
        ];
        if ($localActivated && $localActivated > 0) {
            $fileBehavior['path'] = '{model}{DS}';
        } else {
            $fileBehavior['filesystem'] = [
                'adapter' => $filesystem,
            ];
            $fileBehavior['path'] = $path.'{model}{DS}{field}{DS}';
        }

        $this->addBehavior('Timestamp');
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'file'=> $fileBehavior
        ]);

        $this->hasMany('Customers', [
            'foreignKey' => 'charge_id'
        ]);
        $this->hasMany('Obligations', [
            'foreignKey' => 'charge_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('state')
            ->requirePresence('state', 'create')
            ->notEmpty('state');

        $validator
            ->requirePresence('name_file', 'create')
            ->notEmpty('name_file');

        $validator
            ->requirePresence('type_charge', 'create')
            ->notEmpty('type_charge');

        $validator
            ->requirePresence('number_records', 'create')
            ->notEmpty('number_records');

        $validator
            ->integer('records_obligation')
            ->allowEmpty('records_obligation');

        $validator
            ->integer('failed_obligation')
            ->allowEmpty('failed_obligation');

        $validator
            ->integer('records_customer')
            ->allowEmpty('records_customer');

        $validator
            ->integer('failed_customer')
            ->allowEmpty('failed_customer');

        $validator
            ->add('file', 'validExtension', [
                'rule' => ['extension', ['csv','zip'] ],
                'message' => __('These files extension are allowed: .csv, .zip')
            ]);

        return $validator;
    }
}
