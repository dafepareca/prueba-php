<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tickets Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $TicketsStatus
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $TicketsTypesErrors
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $TicketsTypesTitles
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Solveds
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Approveds
 *
 * @property |\Cake\ORM\Association\BelongsTo $TicketsStatus
 * @property \App\Model\Table\TicketsResourcesTable|\Cake\ORM\Association\HasMany $TicketsResources
 *
 * @method \App\Model\Entity\Ticket get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ticket newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Ticket[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ticket|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ticket patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ticket[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ticket findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TicketsTable extends Table
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

        $this->setTable('tickets');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Solveds', [
            'className' => 'Users',
            'foreignKey' => 'solved_by'
        ]);

        $this->belongsTo('Approveds', [
            'className' => 'Users',
            'foreignKey' => 'approved_by'
        ]);
        $this->belongsTo('TicketsStatus', [
            'foreignKey' => 'ticket_state_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('TicketsTypesErrors', [
            'foreignKey' => 'ticket_type_error_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('TicketsResources', [
            'foreignKey' => 'ticket_id'
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
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('ticket_type_error_id', 'create')
            ->notEmpty('ticket_type_error_id');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->integer('solved_by')
            ->allowEmpty('solved_by');

        $validator
            ->allowEmpty('resolved_detail');

        $validator
            ->integer('approved_by')
            ->allowEmpty('approved_by');

        $validator
            ->integer('priority')
            ->requirePresence('priority', 'create')
            ->notEmpty('priority');

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
        $rules->add($rules->existsIn(['ticket_state_id'], 'TicketsStatus'));

        return $rules;
    }
}
