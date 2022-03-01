<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CustomerTypeIdentifications Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\HasMany $Customers
 *
 * @method \App\Model\Entity\CustomerTypeIdentification get($primaryKey, $options = [])
 * @method \App\Model\Entity\CustomerTypeIdentification newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CustomerTypeIdentification[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CustomerTypeIdentification|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CustomerTypeIdentification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerTypeIdentification[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CustomerTypeIdentification findOrCreate($search, callable $callback = null, $options = [])
 */
class CustomerTypeIdentificationsTable extends Table
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

        $this->addBehavior('BeforeDelete');
        $this->setTable('customer_type_identifications');
        $this->setDisplayField('type');
        $this->setPrimaryKey('id');

        $this->hasMany('Customers', [
            'foreignKey' => 'customer_type_identification_id'
        ]);

        $this->hasMany('Users', [
            'foreignKey' => 'type_identification_id'
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
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }
}
