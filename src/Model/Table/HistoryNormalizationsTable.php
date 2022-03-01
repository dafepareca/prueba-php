<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HistoryNormalizations Model
 *
 * @property \App\Model\Table\HistoryCustomersTable|\Cake\ORM\Association\BelongsTo $HistoryCustomers
 *
 * @method \App\Model\Entity\HistoryNormalization get($primaryKey, $options = [])
 * @method \App\Model\Entity\HistoryNormalization newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HistoryNormalization[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HistoryNormalization|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HistoryNormalization patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryNormalization[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryNormalization findOrCreate($search, callable $callback = null, $options = [])
 */
class HistoryNormalizationsTable extends Table
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

        $this->setTable('history_normalizations');
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
            ->decimal('fee')
            ->requirePresence('fee', 'create')
            ->notEmpty('fee');

        $validator
            ->decimal('rate')
            ->requirePresence('rate', 'create')
            ->notEmpty('rate');

        $validator
            ->integer('term')
            ->requirePresence('term', 'create')
            ->notEmpty('term');

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
