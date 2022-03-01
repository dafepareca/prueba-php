<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LogCommercialFixture
 *
 */
class LogCommercialFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'log_commercial';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'log_id' => ['type' => 'integer', 'length' => 15, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'accion' => ['type' => 'string', 'length' => 6, 'null' => true, 'default' => 'INFC', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'type_identification' => ['type' => 'string', 'length' => 2, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'identification' => ['type' => 'integer', 'length' => 15, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fecha' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'hora' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'obligation' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'telefono' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'extension' => ['type' => 'string', 'length' => 6, 'null' => true, 'default' => '00000', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'ciudad' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => '00000000000', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'tipo_telefono' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'tipo_direccion' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'customer_email' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'codigo_gestor' => ['type' => 'string', 'length' => 25, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'codigo_recuperador' => ['type' => 'string', 'length' => 6, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'tipo_resultado' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'contacto' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'motivo_no_pago' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'nivel_ingresos' => ['type' => 'string', 'length' => 4, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'negociacion' => ['type' => 'string', 'length' => 6, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_2' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'hora_2' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'fecha_documentacion' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'fecha_pago' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'valor_negociacion' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'codigo_reporte' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_reporte' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'hora_reporte' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'tarea' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_tarea_desde' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'fecha_tarea_hasta' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'hora_tarea_desde' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'hora_tarea_hasta' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'comentario' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'comentario_terceros' => ['type' => 'string', 'length' => 200, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'consecutivo' => ['type' => 'string', 'length' => 11, 'null' => true, 'default' => '0000000000', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'consecutivo_relativo' => ['type' => 'string', 'length' => 11, 'null' => true, 'default' => '0000000000', 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_generacion' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'hora_generacion' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'estado' => ['type' => 'integer', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fecha_acceso_app' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'motivo_pago_descrip' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'aprobación_tyc' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'id_paquete_documental' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'fecha_agendamiento' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'motivo_agendamiento' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'ip' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'id_session_canal' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
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
            'accion' => 'Lore',
            'type_identification' => '',
            'identification' => 1,
            'fecha' => '2021-05-01 16:57:39',
            'hora' => '2021-05-01 16:57:39',
            'obligation' => 'Lorem ipsum dolor sit amet',
            'telefono' => 'Lorem ipsum d',
            'extension' => 'Lore',
            'ciudad' => 'Lorem ipsum d',
            'tipo_telefono' => 'Lorem ipsum d',
            'tipo_direccion' => 'Lorem ipsum d',
            'customer_email' => 'Lorem ipsum dolor sit amet',
            'codigo_gestor' => 'Lorem ipsum dolor sit a',
            'codigo_recuperador' => 'Lore',
            'tipo_resultado' => 'Lorem ip',
            'contacto' => 'Lorem ipsum dolor sit amet',
            'motivo_no_pago' => 'Lorem ipsum dolor ',
            'nivel_ingresos' => 'Lo',
            'negociacion' => 'Lore',
            'fecha_2' => '2021-05-01 16:57:39',
            'hora_2' => '2021-05-01 16:57:39',
            'fecha_documentacion' => '2021-05-01 16:57:39',
            'fecha_pago' => '2021-05-01',
            'valor_negociacion' => 'Lorem ipsum dolor sit amet',
            'codigo_reporte' => 'Lor',
            'fecha_reporte' => '2021-05-01 16:57:39',
            'hora_reporte' => '2021-05-01 16:57:39',
            'tarea' => 'Lor',
            'fecha_tarea_desde' => '2021-05-01 16:57:39',
            'fecha_tarea_hasta' => '2021-05-01 16:57:39',
            'hora_tarea_desde' => '2021-05-01 16:57:39',
            'hora_tarea_hasta' => '2021-05-01 16:57:39',
            'comentario' => 'Lorem ipsum dolor sit amet',
            'comentario_terceros' => 'Lorem ipsum dolor sit amet',
            'consecutivo' => 'Lorem ips',
            'consecutivo_relativo' => 'Lorem ips',
            'fecha_generacion' => '2021-05-01 16:57:39',
            'hora_generacion' => '2021-05-01 16:57:39',
            'estado' => 1,
            'fecha_acceso_app' => '2021-05-01 16:57:39',
            'motivo_pago_descrip' => 'Lorem ipsum dolor sit amet',
            'aprobación_tyc' => 1,
            'id_paquete_documental' => 'Lorem ipsum dolor sit amet',
            'fecha_agendamiento' => '2021-05-01 16:57:39',
            'motivo_agendamiento' => 'Lorem ipsum dolor sit amet',
            'ip' => 'Lorem ipsum dolor sit amet',
            'id_session_canal' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
