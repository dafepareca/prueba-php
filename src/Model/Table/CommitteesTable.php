<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Committees Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Coordinators
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Asesor
 * @property \App\Model\Table\HistoriesTable|\Cake\ORM\Association\BelongsTo $Histories
 *
 * @method \App\Model\Entity\Committee get($primaryKey, $options = [])
 * @method \App\Model\Entity\Committee newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Committee[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Committee|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Committee patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Committee[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Committee findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CommitteesTable extends Table
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

        $this->setTable('committees');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'coordinator_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Asesor', [
            'foreignKey' => 'asesor_id',
            'joinType' => 'INNER',
            'className' => 'Users'
        ]);

        $this->belongsTo('Coordinador', [
            'foreignKey' => 'coordinator_id',
            'joinType' => 'INNER',
            'className' => 'Users'
        ]);

        $this->belongsTo('HistoryCustomers', [
            'foreignKey' => 'history_id',
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
            ->dateTime('create')
            ->allowEmpty('create');

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
        $rules->add($rules->existsIn(['coordinator_id'], 'Users'));
        $rules->add($rules->existsIn(['history_id'], 'HistoryCustomers'));

        return $rules;
    }
}
