<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * Customers Model
 *
 * @property \Cake\ORM\Association\HasMany $Users
 * @property \Cake\ORM\Association\HasMany $HistoryCustomers
 * @property \Cake\ORM\Association\BelongsToMany $BusinessUnits
 *
 * @method \App\Model\Entity\Customer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Customer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Customer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Customer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Customer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Customer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Customer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \App\Model\Behavior\AuditableBehavior
 */
class CustomersTable extends Table
{
    use CurrentUserTrait;
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('customers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');


        $this->belongsTo('CustomerTypeIdentifications');
        $this->hasMany('Obligations', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('HistoryCustomers', [
            'foreignKey' => 'customer_id'
        ]);
        $this->hasMany('QueriesCustomers', [
            'foreignKey' => 'customer_id'
        ]);
        $this->belongsTo('Charges', [
            'foreignKey' => 'charge_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->allowEmpty('contact_name');

        $validator
            ->allowEmpty('contact_email');

        $validator
            ->allowEmpty('contact_position');

        $validator
            ->allowEmpty('contact_phone');

        return $validator;
    }
}
