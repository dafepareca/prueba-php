<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CndCodes Model
 *
 * @method \App\Model\Entity\CndCode get($primaryKey, $options = [])
 * @method \App\Model\Entity\CndCode newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CndCode[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CndCode|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CndCode patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CndCode[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CndCode findOrCreate($search, callable $callback = null, $options = [])
 */
class CndCodesTable extends Table
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

        $this->setTable('cnd_codes');
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
            ->integer('code')
            ->requirePresence('code', 'create')
            ->notEmpty('code');

        $validator
            ->requirePresence('message', 'create')
            ->notEmpty('message');

        $validator
            ->integer('not_negotiate')
            ->allowEmpty('not_negotiate');

        return $validator;
    }
}
