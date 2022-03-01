<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LogRediferido Model
 *
 * @property \App\Model\Table\LogsTable|\Cake\ORM\Association\BelongsTo $Logs
 *
 * @method \App\Model\Entity\LogRediferido get($primaryKey, $options = [])
 * @method \App\Model\Entity\LogRediferido newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LogRediferido[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LogRediferido|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LogRediferido patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LogRediferido[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LogRediferido findOrCreate($search, callable $callback = null, $options = [])
 */
class LogRediferidoTable extends Table
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

        $this->setTable('log_rediferido');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Logs', [
            'foreignKey' => 'log_id'
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
            ->dateTime('creado')
            ->requirePresence('creado', 'create')
            ->notEmpty('creado');

        $validator
            ->dateTime('modificado')
            ->requirePresence('modificado', 'create')
            ->notEmpty('modificado');

        $validator
            ->requirePresence('type_identification', 'create')
            ->notEmpty('type_identification');

        $validator
            ->requirePresence('identification', 'create')
            ->notEmpty('identification');

        $validator
            ->requirePresence('obligation', 'create')
            ->notEmpty('obligation');

        $validator
            ->allowEmpty('origen');

        $validator
            ->dateTime('fecha_compromiso')
            ->allowEmpty('fecha_compromiso');

        $validator
            ->decimal('pago_real')
            ->allowEmpty('pago_real');

        $validator
            ->integer('plazo')
            ->requirePresence('plazo', 'create')
            ->notEmpty('plazo');

        $validator
            ->allowEmpty('codigo_estrategia');

        $validator
            ->requirePresence('tipo_rediferido', 'create')
            ->notEmpty('tipo_rediferido');

        $validator
            ->boolean('cliente_desiste')
            ->requirePresence('cliente_desiste', 'create')
            ->notEmpty('cliente_desiste');

        $validator
            ->allowEmpty('reversan');

        $validator
            ->integer('sequential_obligation')
            ->requirePresence('sequential_obligation', 'create')
            ->notEmpty('sequential_obligation');

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
        $rules->add($rules->existsIn(['log_id'], 'Logs'));

        return $rules;
    }
}
