<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsersBusinessUnits Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $BusinessUnits
 *
 * @method \App\Model\Entity\UsersBusinessUnit get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsersBusinessUnit newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsersBusinessUnit[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsersBusinessUnit|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersBusinessUnit patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsersBusinessUnit[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsersBusinessUnit findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersBusinessUnitsTable extends Table
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

        $this->setTable('users_business_units');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('BusinessUnits', [
            'foreignKey' => 'business_unit_id',
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['business_unit_id'], 'BusinessUnits'));

        return $rules;
    }
}
