<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LogTransactionalFixture
 *
 */
class LogTransactionalFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'log_transactional';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'log_id' => ['type' => 'integer', 'length' => 15, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fecha' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'type_identification' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'codigo' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'identification' => ['type' => 'integer', 'length' => 15, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'estado' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'hora' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'obligation' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'customer_revenue' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'customer_paid_capacity' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'initial_payment_punished' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'customer_email' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'total_debt' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'previous_minimum_payment' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'initial_fee' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'strategy' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'code_strategy' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'cuota_Proyctada' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'months_term' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'annual_effective_rate' => ['type' => 'decimal', 'length' => 4, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'nominal_rate' => ['type' => 'decimal', 'length' => 4, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'user_dataweb' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'payment_agreed' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'documentation_date' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'office_name' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'documentation_date_2' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'empresa' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'alternativa' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'aprobado_por_comite' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'coordinador' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'reason_rejection' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'initial_condonation' => ['type' => 'decimal', 'length' => 4, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'value_initial_condonation' => ['type' => 'decimal', 'length' => 25, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'end_condonation' => ['type' => 'decimal', 'length' => 4, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'value_end_condonation' => ['type' => 'decimal', 'length' => 25, 'precision' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'pago_total_vehiculo' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'pago_total_vehiculo_experto' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'oferta_cliente_vehiculo' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'date_valorization' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'type_valorization' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'value_valorization' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'value_parking' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'value_subpoena' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'value_taxes' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'value_others' => ['type' => 'decimal', 'length' => 12, 'precision' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'cnd' => ['type' => 'integer', 'length' => 3, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'documentos' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'user_documents_delivered' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'documentos_fecha' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'desiste' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'user_customer_desist' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'desiste_fecha' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'credit_payment_day' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'documents_required' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'is_uvr' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'campania' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'observaciones' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_aceso_app' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'motivo_no_pago' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'celular' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'aprovacion_TyC' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'id_paquete_documental' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_agendamiento' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'motivo_agendamiento' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'ip' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'id_sesion_canal' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'estado_log' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'log_id' => 1,
            'fecha' => '2021-04-30 11:54:22',
            'type_identification' => 'Lorem ipsum dolor sit amet',
            'codigo' => 1,
            'identification' => 1,
            'estado' => 'Lorem ipsum dolor sit amet',
            'hora' => 'Lorem ip',
            'obligation' => 'Lorem ipsum dolor sit amet',
            'customer_revenue' => 1.5,
            'customer_paid_capacity' => 1.5,
            'initial_payment_punished' => 1.5,
            'customer_email' => 'Lorem ipsum dolor sit amet',
            'total_debt' => 1.5,
            'previous_minimum_payment' => 1.5,
            'initial_fee' => 1.5,
            'strategy' => 'Lorem ipsum dolor sit amet',
            'code_strategy' => 'Lorem ip',
            'cuota_Proyctada' => 'Lorem ipsum dolor sit amet',
            'months_term' => 1,
            'annual_effective_rate' => 1.5,
            'nominal_rate' => 1.5,
            'user_dataweb' => 'Lorem ipsum dolor sit amet',
            'payment_agreed' => 1.5,
            'documentation_date' => '2021-04-30 11:54:22',
            'office_name' => 'Lorem ipsum dolor sit amet',
            'documentation_date_2' => '2021-04-30 11:54:22',
            'empresa' => 'Lorem ipsum dolor sit amet',
            'alternativa' => '',
            'aprobado_por_comite' => 1,
            'coordinador' => 'Lorem ipsum dolor sit amet',
            'reason_rejection' => 'Lorem ipsum dolor sit amet',
            'initial_condonation' => 1.5,
            'value_initial_condonation' => 1.5,
            'end_condonation' => 1.5,
            'value_end_condonation' => 1.5,
            'pago_total_vehiculo' => 1.5,
            'pago_total_vehiculo_experto' => 1.5,
            'oferta_cliente_vehiculo' => 1.5,
            'date_valorization' => '2021-04-30 11:54:22',
            'type_valorization' => 'Lorem ipsum dolor ',
            'value_valorization' => 1.5,
            'value_parking' => 1.5,
            'value_subpoena' => 1.5,
            'value_taxes' => 1.5,
            'value_others' => 1.5,
            'cnd' => 1,
            'documentos' => 1,
            'user_documents_delivered' => 'Lorem ipsum dolor sit amet',
            'documentos_fecha' => '2021-04-30 11:54:22',
            'desiste' => 1,
            'user_customer_desist' => 'Lorem ipsum dolor sit amet',
            'desiste_fecha' => '2021-04-30 11:54:22',
            'credit_payment_day' => 1,
            'documents_required' => 1,
            'is_uvr' => 'Lorem ip',
            'campania' => 'Lorem ipsum dolor sit amet',
            'observaciones' => 'Lorem ipsum dolor sit amet',
            'fecha_aceso_app' => '2021-04-30 11:54:22',
            'motivo_no_pago' => 'Lorem ipsum dolor sit amet',
            'celular' => 'Lorem ipsum d',
            'aprovacion_TyC' => 1,
            'id_paquete_documental' => 'Lorem ipsum dolor sit amet',
            'fecha_agendamiento' => '2021-04-30 11:54:22',
            'motivo_agendamiento' => 'Lorem ipsum dolor sit amet',
            'ip' => 'Lorem ipsum dolor sit amet',
            'id_sesion_canal' => 'Lorem ipsum dolor sit amet',
            'estado_log' => 1
        ],
    ];
}
