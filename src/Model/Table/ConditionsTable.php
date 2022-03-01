<?php
namespace App\Model\Table;

use App\Model\Entity\Condition;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Conditions Model
 *
 * @property \App\Model\Table\TypesConditionsTable|\Cake\ORM\Association\BelongsTo $TypesConditions
 *
 * @method \App\Model\Entity\Condition get($primaryKey, $options = [])
 * @method \App\Model\Entity\Condition newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Condition[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Condition|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Condition patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Condition[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Condition findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConditionsTable extends Table
{


    const IGUAL = 1;
    const MAYOR = 2;
    const MAYORIGUAL = 3;
    const MENOR = 4;
    const MENORIGUAL = 5;
    const BETWEEN = 6;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('conditions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('TypesConditions', [
            'foreignKey' => 'type_condition_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('ConditionsP', [
            'foreignKey' => 'condition_id',
            'className' => 'Conditions',
            //'joinType' => 'INNER'
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
            ->requirePresence('operator', 'create')
            ->notEmpty('operator');

        $validator
            ->requirePresence('value', 'create')
            ->notEmpty('value');

        $validator
            ->requirePresence('compare', 'create')
            ->notEmpty('compare');

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
        $rules->add($rules->existsIn(['type_condition_id'], 'TypesConditions'));

        return $rules;
    }

    static function getOperators(){

        $operators = [
            1 => '=',
            2 => '>',
            3 => '>=',
            4 => '<',
            5 => '<=',
            6 => 'Entre',
        ];

        return $operators;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @return array
     */


    function getKeyValuePairs(){
        $conditions = $this->find()
            ->contain(['ConditionsP' => ['sort' => ['ConditionsP.sort' => 'ASC']]])
            ->order(['Conditions.sort' => 'ASC'])
            ->all();

        $condition_key_value_pairs = [];
        /** @var  $condition Condition*/
        foreach ($conditions as $condition){
            $condition_key_value_pairs[$condition->type_condition_id][] = $condition;
        }

        return $condition_key_value_pairs;
    }

    static function getValorCondicion($condiciones, $comparar){

        $valor = null;
        /** @var  $condicion Condition*/
        if(!empty($condiciones)){
            foreach ($condiciones as $index => $condicion) {
                if ($condicion->operator == ConditionsTable::IGUAL){
                    if($comparar == $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MAYOR){
                    if($comparar > $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MAYORIGUAL){
                    if($comparar >= $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MENOR){
                    if($comparar < $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MENORIGUAL){
                    if($comparar <= $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::BETWEEN){
                    $valores = explode(',',$condicion->compare);
                    if(is_array($valores)){
                        if($comparar >= $valores[0] && $comparar<=$valores[1]){
                            $valor = $condicion->value;
                            break;
                        }
                    }
                }
            }

        }
        return $valor;

    }
}
