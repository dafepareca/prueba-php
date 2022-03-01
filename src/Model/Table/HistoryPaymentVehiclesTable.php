<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HistoryPaymentVehicles Model
 *
 * @property \App\Model\Table\HistoryCustomersTable|\Cake\ORM\Association\BelongsTo $HistoryCustomers
 *
 * @method \App\Model\Entity\HistoryPaymentVehicle get($primaryKey, $options = [])
 * @method \App\Model\Entity\HistoryPaymentVehicle newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HistoryPaymentVehicle[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HistoryPaymentVehicle|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HistoryPaymentVehicle patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryPaymentVehicle[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryPaymentVehicle findOrCreate($search, callable $callback = null, $options = [])
 */
class HistoryPaymentVehiclesTable extends Table
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

        $this->setTable('history_payment_vehicles');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('HistoryCustomers', [
            'foreignKey' => 'history_customer_id',
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
            ->decimal('total_payment')
            ->requirePresence('total_payment', 'create')
            ->notEmpty('total_payment');

        $validator
            ->decimal('total_payment_expert')
            ->requirePresence('total_payment_expert', 'create')
            ->notEmpty('total_payment_expert');

        /*$validator
            ->decimal('customer_offer')
            ->requirePresence('customer_offer', 'create')
            ->notEmpty('customer_offer');*/

        $validator
            ->decimal('condonation')
            ->requirePresence('condonation', 'create')
            ->notEmpty('condonation');

        $validator
            ->requirePresence('value_condonation', 'create')
            ->notEmpty('value_condonation');

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
        $rules->add($rules->existsIn(['history_customer_id'], 'HistoryCustomers'));

        return $rules;
    }
}
