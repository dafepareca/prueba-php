<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HistoryCustomers Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\HistoryStatusesTable|\Cake\ORM\Association\BelongsTo $HistoryStatuses
 * @property \App\Model\Table\HistoryStatusesTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\HistoryDetailsTable|\Cake\ORM\Association\HasMany $HistoryDetails
 * @property \App\Model\Table\HistoryNormalizationsTable|\Cake\ORM\Association\HasMany $HistoryNormalizations
 * @property \App\Model\Table\NegotiationReasonTable|\Cake\ORM\Association\BelongsTo $NegotiationReason
 *
 * @method \App\Model\Entity\HistoryCustomer get($primaryKey, $options = [])
 * @method \App\Model\Entity\HistoryCustomer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HistoryCustomer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HistoryCustomer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HistoryCustomer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryCustomer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryCustomer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HistoryCustomersTable extends Table
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

        $this->setTable('history_customers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('HistoryStatuses', [
            'foreignKey' => 'history_status_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('HistoryDetails', [
            'foreignKey' => 'history_customer_id'
        ]);
        $this->hasMany('HistoryNormalizations', [
            'foreignKey' => 'history_customer_id'
        ]);
        $this->hasMany('HistoryPunishedPortfolios', [
            'foreignKey' => 'history_customer_id'
        ]);

        $this->hasMany('HistoryPaymentVehicles', [
            'foreignKey' => 'history_customer_id'
        ]); 
        $this->belongsTo('NegotiationReason', [
            'foreignKey' => 'negotiation_reason_id'
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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        $rules->add($rules->existsIn(['history_status_id'], 'HistoryStatuses'));
        $rules->add($rules->existsIn(['negotiation_reason_id'], 'NegotiationReason'));

        return $rules;
    }
}
