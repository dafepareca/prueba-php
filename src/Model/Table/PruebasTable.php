<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pruebas Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CustomerTypeentificationsTable|\Cake\ORM\Association\BelongsTo $CustomerTypeentifications
 *
 * @method \App\Model\Entity\Prueba get($primaryKey, $options = [])
 * @method \App\Model\Entity\Prueba newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Prueba[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Prueba|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Prueba patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Prueba[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Prueba findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PruebasTable extends Table
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

        $this->setTable('pruebas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('CustomerTypeIdentifications', [
            'foreignKey' => 'customer_type_identification_id',
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
            ->integer('customer_identification')
            ->requirePresence('customer_identification', 'create')
            ->notEmpty('customer_identification');

        $validator
            ->boolean('answer')
            ->allowEmpty('answer');

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
        $rules->add($rules->existsIn(['customer_type_identification_id'], 'CustomerTypeIdentifications'));

        return $rules;
    }
}
