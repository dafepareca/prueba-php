<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TypesConditions Model
 *
 * @method \App\Model\Entity\TypesCondition get($primaryKey, $options = [])
 * @method \App\Model\Entity\TypesCondition newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TypesCondition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TypesCondition|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TypesCondition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TypesCondition[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TypesCondition findOrCreate($search, callable $callback = null, $options = [])
 */
class TypesConditionsTable extends Table
{

    const TASAPISO = 1;
    const NORMALIZACION = 2;
    const REDIFERIDO  = 3;
    const TASAANUALCASTIGADA = 4;
    const PORCENTAJECONDONACION = 5;
    const PORCENTAJEDISMINUCION = 6;
    const PORCENTAJEPAGOMINIMO = 7;
    const POLITICAACIERTA  = 8;
    const TASADESVALORIZACION  = 9;
    const CONDONACIONVIGENTE  = 10;
    const CONDONACIONCASTIGADA  = 11;
    const ZONAGRISISF  = 12;
    const PORCENTAJEPAGOSUGERIDO  = 13;
    const NORMALIZACIONEXPRES  = 14;
    const ACPKEXPRES  = 15;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('types_conditions');
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
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }
}
