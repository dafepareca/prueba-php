<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TicketsTypesErrors Model
 *
 * @method \App\Model\Entity\TicketsTypesError get($primaryKey, $options = [])
 * @method \App\Model\Entity\TicketsTypesError newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TicketsTypesError[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TicketsTypesError|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TicketsTypesError patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TicketsTypesError[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TicketsTypesError findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TicketsTypesErrorsTable extends Table
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

        $this->setTable('tickets_types_errors');
        $this->setDisplayField('type');
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
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }
}
