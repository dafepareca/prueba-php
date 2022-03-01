<?php

namespace App\Model\Table;

use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;


/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Roles
 * @property \Cake\ORM\Association\BelongsTo $UserStatuses
 * @property \Cake\ORM\Association\BelongsTo $AccessGroups
 * @property \Cake\ORM\Association\HasOne $AdminPermissions
 * @property \Cake\ORM\Association\HasOne $Attachments
 * @property \Cake\ORM\Association\HasMany $AccessLogs
 * @property \Cake\ORM\Association\BelongsTo $Business
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdminsTable extends Table
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
        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');

        $this->hasOne('Attachments', [
            'foreignKey' => 'foreign_key',
            'conditions' => ['Attachments.model' => 'User'],
            'dependent' => true
        ]);

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('UserStatuses', [
            'foreignKey' => 'user_status_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
        ]);

        $this->belongsTo('AccessGroups', [
            'foreignKey' => 'access_group_id',
        ]);

        $this->belongsTo('Business', [
            'foreignKey' => 'busines_id',
        ]);

        $this->hasOne('AdminPermissions',[
        'foreignKey' => 'user_id',
        ]);

        $this->hasMany('AccessLogs', [
            'foreignKey' => 'user_id',
            'dependent' => false
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
            ->requirePresence('identification','create')
            ->notEmpty('identification');

        $validator
            ->requirePresence('email', 'create')
            ->notEmpty('email');
           # ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'The email has already been taken']);

        $validator
            ->requirePresence('mobile', 'create')
            ->notEmpty('mobile')
            ->lengthBetween('mobile', [10, 10], 'Mobile invalid');
           # ->add('mobile', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'The mobile has already been taken']);

        $validator
            ->notEmpty('role_id', 'Select role')
            ->notEmpty('user_status_id', 'Select status user')
            ->notEmpty('access_group_id', 'Select role');

        $validator
            ->add('password_update', [
            'length' => [
                'rule' => [
                    'minLength', 6
                ], 'message' => 'The password have to be at least 6 characters!']
            ])
            ->add('password_update', [
                'match' => [
                    'rule' => [
                        'compareWith', 'password_confirm_update'
                    ], 'message' => 'The passwords does not match!']
            ])
            ->allowEmpty('password_update');

        $validator->add('password_confirm_update', [
            'length' => [
                'rule' => [
                    'minLength', 6
                ], 'message' => 'The password have to be at least 6 characters!']
            ])
            ->add('password_confirm_update', [
            'match' => [
                'rule' => [
                    'compareWith', 'password_update'
                ], 'message' => 'The passwords does not match!']
            ])
            ->allowEmpty('password_confirm_update');

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
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        $rules->add($rules->existsIn(['access_group_id'], 'AccessGroups'));
        $rules->add($rules->existsIn(['busines_id'], 'Business'));
        $rules->add($rules->existsIn(['user_status_id'], 'UserStatuses'));
        return $rules;
    }

    /**
     * Archive method
     *
     * Try to archive an entity or throw a PersistenceFailedException if the entity is new,
     * has no primary key value, application rules checks failed or the delete was aborted by a callback.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to remove.
     * @return bool success
     */
    public function archiveUser(Asesor $entity)
    {
        try {
            // Actualizamos los datos del usuario
            $this->archiveUserGeneral($entity);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
