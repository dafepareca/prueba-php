<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StateReasons Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\StateReason get($primaryKey, $options = [])
 * @method \App\Model\Entity\StateReason newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StateReason[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StateReason|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StateReason patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StateReason[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StateReason findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StateReasonsTable extends Table
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

        $this->setTable('state_reasons');
        $this->setDisplayField('state');
        $this->setPrimaryKey('id');

        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Auditable');

        $this->hasMany('Users', [
            'foreignKey' => 'state_reason_id'
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
            ->requirePresence('state', 'create')
            ->notEmpty('state');

        return $validator;
    }
}
