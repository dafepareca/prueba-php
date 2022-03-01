<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * AccessGroups Model
 *
 * @property \Cake\ORM\Association\HasMany $IpsGroups
 * @property \Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\AccessGroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccessGroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccessGroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccessGroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccessGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccessGroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccessGroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AccessGroupsTable extends Table
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

        $this->setTable('access_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');

        $this->hasMany('IpsGroups', [
            'foreignKey' => 'access_group_id',
            'dependent' => true,
            'cascadeCallbacks' => true
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'access_group_id'
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
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'The name has already been taken']);

        $validator
            ->allowEmpty('description');

        $validator
            ->boolean('all_ips')
            ->requirePresence('all_ips', 'create')
            ->notEmpty('all_ips');

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
