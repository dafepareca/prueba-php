<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LogCommercial Model
 *
 * @property \App\Model\Table\LogsTable|\Cake\ORM\Association\BelongsTo $Logs
 *
 * @method \App\Model\Entity\LogCommercial get($primaryKey, $options = [])
 * @method \App\Model\Entity\LogCommercial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LogCommercial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LogCommercial|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LogCommercial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LogCommercial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LogCommercial findOrCreate($search, callable $callback = null, $options = [])
 */
class LogCommercialTable extends Table
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

        $this->setTable('log_commercial');
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
            ->allowEmpty('accion');

        $validator
            ->allowEmpty('type_identification');

        $validator
            ->integer('identification')
            ->allowEmpty('identification');

        $validator
            ->dateTime('fecha')
            ->allowEmpty('fecha');

        $validator
            ->dateTime('hora')
            ->allowEmpty('hora');

        $validator
            ->allowEmpty('obligation');

        $validator
            ->allowEmpty('telefono');

        $validator
            ->allowEmpty('extension');

        $validator
            ->allowEmpty('ciudad');

        $validator
            ->allowEmpty('tipo_telefono');

        $validator
            ->allowEmpty('tipo_direccion');

        $validator
            ->allowEmpty('customer_email');

        $validator
            ->allowEmpty('codigo_gestor');

        $validator
            ->allowEmpty('codigo_recuperador');

        $validator
            ->allowEmpty('tipo_resultado');

        $validator
            ->allowEmpty('contacto');

        $validator
            ->allowEmpty('motivo_no_pago');

        $validator
            ->allowEmpty('nivel_ingresos');

        $validator
            ->allowEmpty('negociacion');

        $validator
            ->dateTime('fecha_2')
            ->allowEmpty('fecha_2');

        $validator
            ->dateTime('hora_2')
            ->allowEmpty('hora_2');

        $validator
            ->dateTime('fecha_documentacion')
            ->allowEmpty('fecha_documentacion');

        $validator
            ->date('fecha_pago')
            ->allowEmpty('fecha_pago');

        $validator
            ->allowEmpty('valor_negociacion');

        $validator
            ->allowEmpty('codigo_reporte');

        $validator
            ->dateTime('fecha_reporte')
            ->allowEmpty('fecha_reporte');

        $validator
            ->dateTime('hora_reporte')
            ->allowEmpty('hora_reporte');

        $validator
            ->allowEmpty('tarea');

        $validator
            ->dateTime('fecha_tarea_desde')
            ->allowEmpty('fecha_tarea_desde');

        $validator
            ->dateTime('fecha_tarea_hasta')
            ->allowEmpty('fecha_tarea_hasta');

        $validator
            ->dateTime('hora_tarea_desde')
            ->allowEmpty('hora_tarea_desde');

        $validator
            ->dateTime('hora_tarea_hasta')
            ->allowEmpty('hora_tarea_hasta');

        $validator
            ->allowEmpty('comentario');

        $validator
            ->allowEmpty('comentario_terceros');

        $validator
            ->allowEmpty('consecutivo');

        $validator
            ->allowEmpty('consecutivo_relativo');

        $validator
            ->dateTime('fecha_generacion')
            ->allowEmpty('fecha_generacion');

        $validator
            ->dateTime('hora_generacion')
            ->allowEmpty('hora_generacion');

        $validator
            ->integer('estado')
            ->allowEmpty('estado');

        $validator
            ->dateTime('fecha_acceso_app')
            ->allowEmpty('fecha_acceso_app');

        $validator
            ->allowEmpty('motivo_pago_descrip');

        $validator
            ->integer('aprobación_tyc')
            ->allowEmpty('aprobación_tyc');

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
            ->allowEmpty('id_session_canal');

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
