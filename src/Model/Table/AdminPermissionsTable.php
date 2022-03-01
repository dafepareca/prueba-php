<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdminPermissions Model
 *
 * @method \App\Model\Entity\AdminPermission get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdminPermission newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdminPermission[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdminPermission|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminPermission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdminPermission[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdminPermission findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdminPermissionsTable extends Table
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

        $this->setTable('admin_permissions');
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
            ->boolean('data')
            ->allowEmpty('data');

        $validator
            ->boolean('create_profile')
            ->allowEmpty('create_profile');

        $validator
            ->boolean('report')
            ->allowEmpty('report');

        $validator
            ->boolean('params')
            ->allowEmpty('params');

        return $validator;
    }
}
