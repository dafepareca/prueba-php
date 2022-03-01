<?php
namespace App\Model\Table;

use App\Model\Entity\ExternalCustomer;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\ORM\Entity;
use App\Model\Table\CurrentUserTrait;

/**
 * ExternalCustomers Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Roles
 * @property \Cake\ORM\Association\BelongsTo $AccessGroups
 * @property \Cake\ORM\Association\BelongsTo $UserStatuses
 * @property \Cake\ORM\Association\HasOne $Attachments
 * @property \Cake\ORM\Association\BelongsTo $Customers
 * @property \Cake\ORM\Association\BelongsToMany $Campaigns
 * @property \Cake\ORM\Association\HasMany $CampaignsExternalCustomers
 *
 * @method \App\Model\Entity\ExternalCustomer get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExternalCustomer newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExternalCustomer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExternalCustomer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExternalCustomer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExternalCustomer[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExternalCustomer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \App\Model\Behavior\BeforeDeleteBehavior
 */
class ExternalCustomersTable extends Table
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

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);

        $this->hasOne('Attachments', [
            'foreignKey' => 'foreign_key',
            'conditions' => ['Attachments.model' => 'User'],
            'dependent' => true
        ]);

        $this->belongsToMany('Campaigns', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'campaign_id',
            'joinTable' => 'campaigns_users',
        ]);

        $this->hasMany('CampaignsExternalCustomers', [
            'foreignKey' => 'user_id',
            'unique' => true,
            'dependent' => true,
            'cascadeCallbacks' => true
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
            ->notEmpty('customer_id', 'Select customer')
            ->notEmpty('user_status_id', 'Select status user')
            ->notEmpty('access_group_id', 'Select role');

        $validator
            ->requirePresence('campaigns_external_customers')
            ->add('campaigns_external_customers', 'custom', [
                'rule' => function($value, $context) {
                    if(count($value) > 0){
                        return true;
                    }
                    return false;
                },
                'message' => 'Please choose at least one Campaign'
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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        $rules->add($rules->existsIn(['access_group_id'], 'AccessGroups'));
        $rules->add($rules->existsIn(['user_status_id'], 'UserStatuses'));
        return $rules;
    }

    function beforeSave(Event $event, Entity $entity){
        if(!$entity->isNew()) {
            $ids = [];
            $ids[] = 0;
            foreach($entity->campaigns_external_customers as  $campaigns_external_customer){
                if(isset($campaigns_external_customer->id) && $campaigns_external_customer->id !== null){
                    $ids[] = $campaigns_external_customer->id;
                }
            }
            $this->CampaignsExternalCustomers->deleteAll([
                'user_id' => $entity->id,
                'id NOT IN' => $ids
            ]);
        }
        return true;
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
    public function archiveUser(ExternalCustomer $entity ){
        try{
            // Eliminamos los registros asociados
            $this->CampaignsExternalCustomers->deleteAll([
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
