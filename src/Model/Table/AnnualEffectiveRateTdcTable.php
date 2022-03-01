<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AnnualEffectiveRateTdc Model
 *
 * @method \App\Model\Entity\AnnualEffectiveRateTdc get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnnualEffectiveRateTdc newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AnnualEffectiveRateTdc[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnnualEffectiveRateTdc|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnnualEffectiveRateTdc patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnnualEffectiveRateTdc[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnnualEffectiveRateTdc findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AnnualEffectiveRateTdcTable extends Table
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

        $this->setTable('annual_effective_rate_tdc');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Auditable');
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
            ->decimal('rate')
            ->notEmpty('rate');

        $validator
            ->date('fecha',['ym'])
            ->requirePresence('fecha', 'create')
            ->notEmpty('fecha');

        return $validator;
    }
}
