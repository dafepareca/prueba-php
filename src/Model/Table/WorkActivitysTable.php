<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * WorkActivitys Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\HasMany $Customers
 *
 * @method \App\Model\Entity\WorkActivity get($primaryKey, $options = [])
 * @method \App\Model\Entity\WorkActivity newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\WorkActivity[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WorkActivity|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WorkActivity patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\WorkActivity[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\WorkActivity findOrCreate($search, callable $callback = null, $options = [])
 */
class WorkActivitysTable extends Table
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

        $this->setTable('work_activitys');
        $this->setDisplayField('activity');
        $this->setPrimaryKey('id');

        $this->hasMany('Customers', [
            'foreignKey' => 'work_activity_id'
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
            ->requirePresence('activity', 'create')
            ->notEmpty('activity');

        return $validator;
    }
}
