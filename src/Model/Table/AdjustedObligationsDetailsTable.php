<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdjustedObligationsDetails Model
 *
 * @property \App\Model\Table\AdjustedObligationsTable|\Cake\ORM\Association\BelongsTo $AdjustedObligations
 *
 * @method \App\Model\Entity\AdjustedObligationsDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdjustedObligationsDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdjustedObligationsDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdjustedObligationsDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdjustedObligationsDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdjustedObligationsDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdjustedObligationsDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class AdjustedObligationsDetailsTable extends Table
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

        $this->setTable('adjusted_obligations_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('AdjustedObligations', [
            'foreignKey' => 'adjusted_obligation_id'
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
            ->requirePresence('obligation', 'create')
            ->notEmpty('obligation');

        $validator
            ->requirePresence('strategy', 'create')
            ->notEmpty('strategy');

        $validator
            ->decimal('total_debt')
            ->requirePresence('total_debt', 'create')
            ->notEmpty('total_debt');

        $validator
            ->decimal('annual_effective_rate')
            ->allowEmpty('annual_effective_rate');

        $validator
            ->decimal('nominal_rate')
            ->allowEmpty('nominal_rate');

        $validator
            ->decimal('monthly_rate')
            ->allowEmpty('monthly_rate');

        $validator
            ->integer('months_term')
            ->allowEmpty('months_term');

        $validator
            ->decimal('customer_revenue')
            ->allowEmpty('customer_revenue');

        $validator
            ->decimal('customer_paid_capacity')
            ->allowEmpty('customer_paid_capacity');

        $validator
            ->decimal('previous_minimum_payment')
            ->allowEmpty('previous_minimum_payment');

        $validator
            ->integer('initial_fee')
            ->allowEmpty('initial_fee');

        $validator
            ->integer('new_fee')
            ->allowEmpty('new_fee');

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
        $rules->add($rules->existsIn(['adjusted_obligation_id'], 'AdjustedObligations'));

        return $rules;
    }
}
