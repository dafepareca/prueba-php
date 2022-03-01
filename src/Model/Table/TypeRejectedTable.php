<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TypeRejected Model
 *
 * @method \App\Model\Entity\TypeRejected get($primaryKey, $options = [])
 * @method \App\Model\Entity\TypeRejected newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TypeRejected[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TypeRejected|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TypeRejected patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TypeRejected[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TypeRejected findOrCreate($search, callable $callback = null, $options = [])
 */
class TypeRejectedTable extends Table
{

    const ZONAGRIS=1;
    const PLAZO=2;
    const TASAPONDERADA=3;
    const TASASECANTE=4;
    const POLITICA=5;
    const NONEGOCIABLE=6;
    const TASAS=7;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('type_rejected');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        return $validator;
    }
}
