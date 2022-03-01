<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AccessLogs Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $LogoutsTypes
 * @property \Cake\ORM\Association\HasMany $Audits
 *
 * @method \App\Model\Entity\AccessLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccessLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccessLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccessLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccessLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccessLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccessLog findOrCreate($search, callable $callback = null, $options = [])
 */
class AccessLogsTable extends Table
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

        $this->setTable('access_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('LogoutsTypes', [
            'foreignKey' => 'logout_type_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Audits', [
            'foreignKey' => 'source_id',
            'className' => 'Audits'
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
            ->allowEmpty('ip');

        $validator
            ->allowEmpty('user_agent');

        $validator
            ->dateTime('date_login')
            ->allowEmpty('date_login');

        $validator
            ->dateTime('date_logout')
            ->allowEmpty('date_logout');

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

        return $rules;
    }
}
