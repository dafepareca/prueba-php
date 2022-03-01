<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Business Model
 *
 * @method \App\Model\Entity\Busines get($primaryKey, $options = [])
 * @method \App\Model\Entity\Busines newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Busines[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Busines|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Busines patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Busines[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Busines findOrCreate($search, callable $callback = null, $options = [])
 *
 * @property \Cake\ORM\Association\HasMany $Users
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BusinessTable extends Table
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

        $this->setTable('business');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Auditable');
        $this->addBehavior('BeforeDelete');

        $this->hasMany('Users', [
            'foreignKey' => 'busines_id',
            'className' => 'Users'
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
            ->requirePresence('nit')
            ->notEmpty('nit');
        $validator
            ->requirePresence('name_contact')
            ->notEmpty('name_contact');
        $validator
            ->requirePresence('phone_contact')
            ->notEmpty('phone_contact');
        $validator
            ->requirePresence('access_group_id')
            ->notEmpty('access_group_id');

        return $validator;
    }
}
