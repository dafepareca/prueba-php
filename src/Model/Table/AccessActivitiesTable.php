<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AccessActivities Model
 *
 * @property \Cake\ORM\Association\BelongsTo $AccessLogs
 * @property \Cake\ORM\Association\BelongsTo $AccessTypesActivities
 *
 * @method \App\Model\Entity\AccessActivity get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccessActivity newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccessActivity[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccessActivity|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccessActivity patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccessActivity[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccessActivity findOrCreate($search, callable $callback = null, $options = [])
 */
class AccessActivitiesTable extends Table
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

        $this->setTable('access_activities');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('CounterCache', ['AccessLogs' => ['access_activity_log_count']]);


        $this->belongsTo('AccessLogs', [
            'foreignKey' => 'access_log_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AccessTypesActivities', [
            'foreignKey' => 'type_activity_id',
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
            ->allowEmpty('model');

        $validator
            ->dateTime('date')
            ->allowEmpty('date');

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
        $rules->add($rules->existsIn(['access_log_id'], 'AccessLogs'));
        $rules->add($rules->existsIn(['type_activity_id'], 'AccessTypesActivities'));

        return $rules;
    }
}
