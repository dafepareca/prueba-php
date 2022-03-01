<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * IpsGroups Model
 *
 * @property \Cake\ORM\Association\BelongsTo $AccessGroups
 *
 * @method \App\Model\Entity\IpsGroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\IpsGroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IpsGroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IpsGroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IpsGroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IpsGroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IpsGroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class IpsGroupsTable extends Table
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

        $this->setTable('ips_groups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');

        $this->belongsTo('AccessGroups', [
            'foreignKey' => 'access_group_id',
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('ip_address', 'create')
            ->notEmpty('ip_address');

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
        $rules->add($rules->existsIn(['access_group_id'], 'AccessGroups'));

        return $rules;
    }
}
