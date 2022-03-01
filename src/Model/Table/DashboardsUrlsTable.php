<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * DashboardsUrls Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Dashboards
 *
 * @method \App\Model\Entity\DashboardsUrl get($primaryKey, $options = [])
 * @method \App\Model\Entity\DashboardsUrl newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DashboardsUrl[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DashboardsUrl|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DashboardsUrl patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DashboardsUrl[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DashboardsUrl findOrCreate($search, callable $callback = null, $options = [])
 */
class DashboardsUrlsTable extends Table
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

        $this->setTable('dashboards_urls');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');

        $this->belongsTo('Dashboards', [
            'foreignKey' => 'dashboard_id',
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
            ->requirePresence('url', 'create')
            ->notEmpty('url');

        $validator
            ->time('hora_inicio')
            ->requirePresence('hora_inicio', 'create')
            ->notEmpty('hora_inicio');

        $validator
            ->time('hora_fin')
            ->requirePresence('hora_fin', 'create')
            ->notEmpty('hora_fin');

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
        $rules->add($rules->existsIn(['dashboard_id'], 'Dashboards'));

        return $rules;
    }
}
