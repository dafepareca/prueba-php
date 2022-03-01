<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomersBusinessUnits Model
 *
 * @property \Cake\ORM\Association\BelongsTo $BusinessUnits
 * @property \Cake\ORM\Association\BelongsTo $Customers
 *
 * @method \App\Model\Entity\CustomersBusinessUnit get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomersBusinessUnit newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomersBusinessUnit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomersBusinessUnit|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomersBusinessUnit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomersBusinessUnit[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomersBusinessUnit findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomersBusinessUnitsTable extends Table
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

        $this->setTable('customers_business_units');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('BusinessUnits', [
            'foreignKey' => 'business_unit_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);

        $this->addBehavior('CounterCache', [
            'Customers' => ['business_units_count']
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
        $rules->add($rules->existsIn(['business_unit_id'], 'BusinessUnits'));
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));

        return $rules;
    }
}
