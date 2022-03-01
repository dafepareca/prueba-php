<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NegotiationReason Model
 *
 * @property \App\Model\Table\HistoryCustomersTable|\Cake\ORM\Association\HasMany $HistoryCustomers
 *
 * @method \App\Model\Entity\NegotiationReason get($primaryKey, $options = [])
 * @method \App\Model\Entity\NegotiationReason newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NegotiationReason[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NegotiationReason|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NegotiationReason patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NegotiationReason[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NegotiationReason findOrCreate($search, callable $callback = null, $options = [])
 */
class NegotiationReasonTable extends Table
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

        $this->setTable("negotiation_reason");
        $this->setDisplayField('idnegotiation_reason');
        $this->setPrimaryKey('idnegotiation_reason');

        $this->hasMany('HistoryCustomers', [
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
            ->integer('idnegotiation_reason')
            ->allowEmpty('idnegotiation_reason', 'create');

        $validator
            ->integer('id_reason')
            ->requirePresence('id_reason', 'create')
            ->notEmpty('id_reason');

        $validator
            ->requirePresence('Descption_reason', 'create')
            ->notEmpty('Descption_reason');

        $validator
            ->allowEmpty('code_reason');

        $validator
            ->allowEmpty('codigo_terceros');

        return $validator;
    }
}
