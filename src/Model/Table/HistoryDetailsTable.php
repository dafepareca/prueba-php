<?php
namespace App\Model\Table;

use App\Model\Entity\HistoryDetail;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HistoryDetails Model
 *
 * @property \App\Model\Table\TypeObligationsTable|\Cake\ORM\Association\BelongsTo $TypeObligations
 * @property \App\Model\Table\HistoryCustomersTable|\Cake\ORM\Association\BelongsTo $HistoryCustomers
 *
 * @method \App\Model\Entity\HistoryDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\HistoryDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HistoryDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HistoryDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HistoryDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class HistoryDetailsTable extends Table
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

        $this->setTable('history_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('TypeObligations', [
            'foreignKey' => 'type_obligation_id',
            'joinType' => 'INNER'
        ]);
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
            ->requirePresence('obligation', 'create')
            ->notEmpty('obligation');

        $validator
            ->requirePresence('strategy', 'create')
            ->notEmpty('strategy');

        $validator
            ->integer('term')
            ->allowEmpty('term');

        $validator
            ->allowEmpty('new _fee');

        $validator
            ->boolean('selected')
            ->allowEmpty('selected');

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
        $rules->add($rules->existsIn(['type_obligation_id'], 'TypeObligations'));
        $rules->add($rules->existsIn(['history_customer_id'], 'HistoryCustomers'));

        return $rules;
    }

    public function findAll(Query $query, array $options)
    {
        
        return $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
            return $results->map(function ($row) {
                /** @var  $row HistoryDetail */

                // $row->maskobligation = substr($row->obligation, 0, 4) . '*****' . substr($row->obligation, -4);
                $row->maskobligation = $row->obligation;
                return $row;
            });
        });
    }
}
