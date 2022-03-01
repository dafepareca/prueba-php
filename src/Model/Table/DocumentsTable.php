<?php
namespace App\Model\Table;

use Cake\Cache\Cache;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Ftp as Adapter;
use App\Model\Table\CurrentUserTrait;

/**
 * Documents Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Campaigns
 * @property \Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Document get($primaryKey, $options = [])
 * @method \App\Model\Entity\Document newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Document[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Document|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Document patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Document[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Document findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\CounterCacheBehavior
 */
class DocumentsTable extends Table
{
    use CurrentUserTrait;
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

        $filesystem = new Adapter([
            'host' => $server,
            'username' => $user,
            'password' => $password,
            'port' => $port
        ]);

        $this->setTable('documents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');
        $this->addBehavior('CounterCache', ['Campaigns' => ['document_count']]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'file'=>[
                'fields' => [
                    'dir' => 'file_dir'
                ],
                'pathProcessor' => 'App\File\Path\DefaultProcessor',
                'filesystem' => [
                    'adapter' => $filesystem,
                ],
                'path' => $path.'{model}{DS}{field}{DS}{campaign}{DS}'
            ],
        ]);

        $this->belongsTo('Campaigns', [
            'foreignKey' => 'campaign_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->requirePresence('file', 'create')
            ->notEmpty('file',__('File is required'),'create');

        $validator
            ->add('file', 'validExtension', [
                'rule' => ['extension', ['pdf', 'txt', 'zip', 'csv', 'xls', 'xlsm', 'xlsx'] ],
                'message' => __('These files extension are allowed: .pdf, .txt, .zip, .csv, .xls, .xlsx')
            ]);


        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('access_public','create')
            ->notEmpty('access_public');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['campaign_id'], 'Campaigns'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function renameUploadedPhoto(array $data, array $settings)
    {
        $ext = substr(strrchr($data['name'], '.'), 1);
        return Text::uuid() . '.' . $ext;
    }
}
