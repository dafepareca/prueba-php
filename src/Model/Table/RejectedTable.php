<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Rejected Model
 *
 * @property \App\Model\Table\TypeRejectedsTable|\Cake\ORM\Association\BelongsTo $TypeRejecteds
 * @property \App\Model\Table\HistoryCustomersTable|\Cake\ORM\Association\BelongsTo $HistoryCustomers
 *
 * @method \App\Model\Entity\Rejected get($primaryKey, $options = [])
 * @method \App\Model\Entity\Rejected newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Rejected[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Rejected|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rejected patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Rejected[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Rejected findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RejectedTable extends Table
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

        $this->setTable('rejected');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TypeRejected', [
            'foreignKey' => 'type_rejected_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('HistoryCustomers', [
            'foreignKey' => 'history_customer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CustomerTypeIdentifications', [
            'foreignKey' => 'customer_type_identification_id',
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
        $rules->add($rules->existsIn(['type_rejected_id'], 'TypeRejected'));
        $rules->add($rules->existsIn(['history_customer_id'], 'HistoryCustomers'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['customer_type_identification_id'], 'CustomerTypeIdentifications'));

        return $rules;
    }
}
