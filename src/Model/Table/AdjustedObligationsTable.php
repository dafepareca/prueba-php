<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdjustedObligations Model
 *
 * @method \App\Model\Entity\AdjustedObligation get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdjustedObligation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdjustedObligation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdjustedObligation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdjustedObligation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdjustedObligation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdjustedObligation findOrCreate($search, callable $callback = null, $options = [])
 */
class AdjustedObligationsTable extends Table
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

        $this->setTable('adjusted_obligations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $options = [
            // Refer to php.net fgetcsv for more information
            'length' => 0,
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
            // Generates a Model.field headings row from the csv file
            'headers' => true,
            // If true, String $content is the data, not a path to the file
            'text' => false,
        ];

        $this->addBehavior('Csv', $options);

        $this->hasMany('AdjustedObligationsDetails', [
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
            ->integer('type_identification')
            ->requirePresence('type_identification', 'create')
            ->notEmpty('type_identification');

        $validator
            ->integer('identification')
            ->requirePresence('identification', 'create')
            ->notEmpty('identification');

        $validator
            ->decimal('customer_revenue')
            ->allowEmpty('customer_revenue');

        $validator
            ->decimal('customer_paid_capacity')
            ->allowEmpty('customer_paid_capacity');

        $validator
            ->decimal('payment_agreed')
            ->allowEmpty('payment_agreed');

        $validator
            ->decimal('previous_minimum_payment')
            ->allowEmpty('previous_minimum_payment');

        $validator
            ->date('documentation_date')
            ->allowEmpty('documentation_date');

        $validator
            ->allowEmpty('office_name');

        $validator
            ->allowEmpty('customer_email');

        $validator
            ->allowEmpty('customer_telephone');

        $validator
            ->dateTime('date_negotiation')
            ->allowEmpty('date_negotiation');

        $validator
            ->allowEmpty('user_dataweb');

        return $validator;
    }
}
