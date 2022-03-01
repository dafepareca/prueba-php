<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LogsFiles Model
 *
 * @method \App\Model\Entity\LogsFile get($primaryKey, $options = [])
 * @method \App\Model\Entity\LogsFile newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LogsFile[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LogsFile|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LogsFile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LogsFile[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LogsFile findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LogsFilesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('logs_files');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->requirePresence('name_file', 'create')
            ->notEmpty('name_file');

        $validator
            ->requirePresence('file_dir', 'create')
            ->notEmpty('file_dir');

        return $validator;
    }
}
