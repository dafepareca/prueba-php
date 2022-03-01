<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * UserStatuses Model
 *
 * @property \Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\UserStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class UserStatusesTable extends Table
{
    use CurrentUserTrait;

    const Active    = 1;
    const Inactive  = 2;
    const Archived  = 3;
    const Bloqueado  = 4;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('user_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');

        $this->hasMany('Users', [
            'foreignKey' => 'user_status_id'
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
            ->requirePresence('label', 'create')
            ->notEmpty('label');

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
        $rules->add($rules->isUnique(['name']));
        return $rules;
    }
}
