<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ModalMessageFixture
 *
 */
class ModalMessageFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'modal_message';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'Columna identidad de la tabla, llave primaria e indice primario, relacionada en el codigo de la siguiente manera:

1	SIN INTENTOS PARA NEGOCIAR
2	SIN CREDITOS PARA SELECCIONAR
3	FIRMA PAGARE MULTITULAR
4	FIRMA PAGARE LEASING
5	FIRMA PAGARE COMPAÑÍA 5
6	CNDCODE NO NEGOCIABLE
7	MAXIMO INTENTOS OTP
8	SIN CAPACIDAD DE PAGO
9	SIN OPCIONES DE NEGOCIACION
', 'precision' => null, 'autoIncrement' => null],
        'titulo' => ['type' => 'string', 'length' => 50, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => 'Columna que almacena el titulo que se mostrara en la modal parametrica', 'precision' => null, 'fixed' => null],
        'mensaje' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => 'Columna que almacena el mensaje que se mostrara en la modal parametrica', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'id' => ['type' => 'unique', 'columns' => ['id'], 'length' => []],
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
            'titulo' => 'Lorem ipsum dolor sit amet',
            'mensaje' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
