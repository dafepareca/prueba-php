<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * Dashboards Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Campaigns
 * @property \Cake\ORM\Association\HasMany $DashboardsUrls
 *
 * @method \App\Model\Entity\Dashboard get($primaryKey, $options = [])
 * @method \App\Model\Entity\Dashboard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Dashboard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Dashboard|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Dashboard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Dashboard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Dashboard findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\CounterCacheBehavior
 */
class DashboardsTable extends Table
{
    use CurrentUserTrait;

    const TIME = [
        5 => '5 Minutes',
        10 => '10 Minutes',
        15 => '15 Minutes',
        20 => '20 Minutes',
        25=> '25 Minutes',
        20 => '30 Minutes',
        60 => '60 Minutes',
    ];
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('dashboards');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');
        $this->addBehavior('CounterCache', ['Campaigns' => ['dashboard_count']]);

        $this->belongsTo('Campaigns', [
            'foreignKey' => 'campaign_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('DashboardsUrls', [
            'foreignKey' => 'dashboard_id'
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
            ->allowEmpty('description');

        $validator
            ->integer('status')
            ->allowEmpty('status');

        $validator
            ->allowEmpty('access_public');

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
        $rules->add($rules->existsIn(['campaign_id'], 'Campaigns'));

        return $rules;
    }
}
