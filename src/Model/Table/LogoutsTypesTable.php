<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LogoutsTypes Model
 *
 * @method \App\Model\Entity\LogoutsType get($primaryKey, $options = [])
 * @method \App\Model\Entity\LogoutsType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LogoutsType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LogoutsType|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LogoutsType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LogoutsType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LogoutsType findOrCreate($search, callable $callback = null, $options = [])
 */
class LogoutsTypesTable extends Table
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

        $this->setTable('logouts_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }
}
