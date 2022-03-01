<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NormalizationReasons Model
 *
 * @property \App\Model\Table\NormalizationsTable|\Cake\ORM\Association\HasMany $Normalizations
 *
 * @method \App\Model\Entity\NormalizationReason get($primaryKey, $options = [])
 * @method \App\Model\Entity\NormalizationReason newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NormalizationReason[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NormalizationReason|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NormalizationReason patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NormalizationReason[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NormalizationReason findOrCreate($search, callable $callback = null, $options = [])
 */
class NormalizationReasonsTable extends Table
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

        $this->setTable('normalization_reasons');
        $this->setDisplayField('reason');
        $this->setPrimaryKey('id');

        $this->hasMany('Normalizations', [
            'foreignKey' => 'normalization_reason_id'
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
            ->requirePresence('reason', 'create')
            ->notEmpty('reason');

        return $validator;
    }
}
