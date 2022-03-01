<?php
namespace App\Model\Table;

use App\Model\Entity\UnitManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * UnitManagers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Roles
 * @property \Cake\ORM\Association\BelongsTo $AccessGroups
 * @property \Cake\ORM\Association\BelongsTo $UserStatuses
 * @property \Cake\ORM\Association\belongsToMany $BusinessUnits
 * @property \Cake\ORM\Association\HasOne $Attachments
 * @property \Cake\ORM\Association\HasMany $UsersBusinessUnits
 *
 * @method \App\Model\Entity\UnitManager get($primaryKey, $options = [])
 * @method \App\Model\Entity\UnitManager newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UnitManager[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UnitManager|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UnitManager patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UnitManager[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UnitManager findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UnitManagersTable extends Table
{
    use CurrentUserTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config){
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');

        $this->belongsTo('Roles', [
            'foreignKey' => 'role_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('UserStatuses', [
            'foreignKey' => 'user_status_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('AccessGroups', [
            'foreignKey' => 'access_group_id',
        ]);

        $this->hasOne('Attachments', [
            'foreignKey' => 'foreign_key',
            'conditions' => ['Attachments.model' => 'User'],
            'dependent' => true
        ]);

        $this->belongsToMany('BusinessUnits', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'business_unit_id',
            'joinTable' => 'users_business_units',
            'sort' => [ 'BusinessUnits.name' => 'ASC' ],
        ]);

        $this->hasMany('AccessLogs', [
            'foreignKey' => 'user_id',
            'dependent' => false
        ]);

        $this->hasMany('UsersBusinessUnits', [
            'foreignKey' => 'user_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator){
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('email', 'create')
            ->notEmpty('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'The email has already been taken']);

        $validator
            ->requirePresence('mobile', 'create')
            ->notEmpty('mobile')
            ->lengthBetween('mobile', [10,10], 'Mobile invalid')
            ->add('mobile', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'The mobile has already been taken']);

        $validator
            ->notEmpty('role_id', 'Select role')
            ->notEmpty('user_status_id', 'Select status user')
            ->notEmpty('access_group_id', 'Select role');

        $validator
            ->add('business_units', 'custom', [
                'rule' => function($value, $context) {
                    return (!empty($value['_ids']) && is_array($value['_ids']));
                },
                'message' => 'Please choose at least one Business Unit'
            ]);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules){
        $rules->add($rules->existsIn(['role_id'], 'Roles'));
        $rules->add($rules->existsIn(['access_group_id'], 'AccessGroups'));
        $rules->add($rules->existsIn(['user_status_id'], 'UserStatuses'));
        return $rules;
    }

    /**
     * Archive method
     *
     * Try to delete an entity or throw a PersistenceFailedException if the entity is new,
     * has no primary key value, application rules checks failed or the delete was aborted by a callback.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to remove.
     * @return bool success
     */
    public function archiveUser(UnitManager $entity ){
        try{
            // Eliminamos los registros asociados
            $this->UsersBusinessUnits->deleteAll([
                'user_id' => $entity->id
            ]);
            // Actualizamos los datos del usuario
            $this->archiveUserGeneral($entity);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
}
