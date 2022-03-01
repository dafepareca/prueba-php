<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LogTransactional Model
 *
 * @property \App\Model\Table\LogsTable|\Cake\ORM\Association\BelongsTo $Logs
 *
 * @method \App\Model\Entity\LogTransactional get($primaryKey, $options = [])
 * @method \App\Model\Entity\LogTransactional newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LogTransactional[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LogTransactional|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LogTransactional patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LogTransactional[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LogTransactional findOrCreate($search, callable $callback = null, $options = [])
 */
class LogTransactionalTable extends Table
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

        $this->setTable('log_transactional');
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
            ->dateTime('fecha')
            ->requirePresence('fecha', 'create')
            ->notEmpty('fecha');

        $validator
            ->allowEmpty('type_identification');

        $validator
            ->integer('codigo')
            ->allowEmpty('codigo');

        $validator
            ->integer('identification')
            ->requirePresence('identification', 'create')
            ->notEmpty('identification');

        $validator
            ->allowEmpty('estado');

        $validator
            ->allowEmpty('hora');

        $validator
            ->allowEmpty('obligation');

        $validator
            ->decimal('customer_revenue')
            ->allowEmpty('customer_revenue');

        $validator
            ->decimal('customer_paid_capacity')
            ->allowEmpty('customer_paid_capacity');

        $validator
            ->decimal('initial_payment_punished')
            ->allowEmpty('initial_payment_punished');

        $validator
            ->allowEmpty('customer_email');

        $validator
            ->decimal('total_debt')
            ->allowEmpty('total_debt');

        $validator
            ->decimal('previous_minimum_payment')
            ->allowEmpty('previous_minimum_payment');

        $validator
            ->decimal('initial_fee')
            ->allowEmpty('initial_fee');

        $validator
            ->allowEmpty('strategy');

        $validator
            ->allowEmpty('code_strategy');

        $validator
            ->allowEmpty('cuota_Proyctada');

        $validator
            ->integer('months_term')
            ->allowEmpty('months_term');

        $validator
            ->decimal('annual_effective_rate')
            ->allowEmpty('annual_effective_rate');

        $validator
            ->decimal('nominal_rate')
            ->allowEmpty('nominal_rate');

        $validator
            ->allowEmpty('user_dataweb');

        $validator
            ->decimal('payment_agreed')
            ->allowEmpty('payment_agreed');

        $validator
            ->dateTime('documentation_date')
            ->allowEmpty('documentation_date');

        $validator
            ->allowEmpty('office_name');

        $validator
            ->dateTime('documentation_date_2')
            ->allowEmpty('documentation_date_2');

        $validator
            ->allowEmpty('empresa');

        $validator
            ->allowEmpty('alternativa');

        $validator
            ->boolean('aprobado_por_comite')
            ->allowEmpty('aprobado_por_comite');

        $validator
            ->allowEmpty('coordinador');

        $validator
            ->allowEmpty('reason_rejection');

        $validator
            ->decimal('initial_condonation')
            ->allowEmpty('initial_condonation');

        $validator
            ->decimal('value_initial_condonation')
            ->allowEmpty('value_initial_condonation');

        $validator
            ->decimal('end_condonation')
            ->allowEmpty('end_condonation');

        $validator
            ->decimal('value_end_condonation')
            ->allowEmpty('value_end_condonation');

        $validator
            ->decimal('pago_total_vehiculo')
            ->allowEmpty('pago_total_vehiculo');

        $validator
            ->decimal('pago_total_vehiculo_experto')
            ->allowEmpty('pago_total_vehiculo_experto');

        $validator
            ->decimal('oferta_cliente_vehiculo')
            ->allowEmpty('oferta_cliente_vehiculo');

        $validator
            ->dateTime('date_valorization')
            ->allowEmpty('date_valorization');

        $validator
            ->allowEmpty('type_valorization');

        $validator
            ->decimal('value_valorization')
            ->allowEmpty('value_valorization');

        $validator
            ->decimal('value_parking')
            ->allowEmpty('value_parking');

        $validator
            ->decimal('value_subpoena')
            ->allowEmpty('value_subpoena');

        $validator
            ->decimal('value_taxes')
            ->allowEmpty('value_taxes');

        $validator
            ->decimal('value_others')
            ->allowEmpty('value_others');

        $validator
            ->integer('cnd')
            ->allowEmpty('cnd');

        $validator
            ->boolean('documentos')
            ->allowEmpty('documentos');

        $validator
            ->allowEmpty('user_documents_delivered');

        $validator
            ->dateTime('documentos_fecha')
            ->allowEmpty('documentos_fecha');

        $validator
            ->boolean('desiste')
            ->allowEmpty('desiste');

        $validator
            ->allowEmpty('user_customer_desist');

        $validator
            ->dateTime('desiste_fecha')
            ->allowEmpty('desiste_fecha');

        $validator
            ->integer('credit_payment_day')
            ->allowEmpty('credit_payment_day');

        $validator
            ->boolean('documents_required')
            ->allowEmpty('documents_required');

        $validator
            ->allowEmpty('is_uvr');

        $validator
            ->allowEmpty('campania');

        $validator
            ->allowEmpty('observaciones');

        $validator
            ->dateTime('fecha_aceso_app')
            ->allowEmpty('fecha_aceso_app');

        $validator
            ->allowEmpty('motivo_no_pago');

        $validator
            ->allowEmpty('celular');

        $validator
            ->integer('aprovacion_TyC')
            ->allowEmpty('aprovacion_TyC');

        $validator
            ->allowEmpty('id_paquete_documental');

        $validator
            ->dateTime('fecha_agendamiento')
            ->allowEmpty('fecha_agendamiento');

        $validator
            ->allowEmpty('motivo_agendamiento');

        $validator
            ->allowEmpty('ip');

        $validator
            ->allowEmpty('id_sesion_canal');

        $validator
            ->integer('estado_log')
            ->allowEmpty('estado_log');

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
