<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ValueStagesFixture
 *
 */
class ValueStagesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'legal_codes_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'city_offices_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'value' => ['type' => 'float', 'length' => 25, 'precision' => 3, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        '_indexes' => [
            'fk_legal_codes_has_city_offices_city_offices1_idx' => ['type' => 'index', 'columns' => ['city_offices_id'], 'length' => []],
            'fk_legal_codes_has_city_offices_legal_codes1_idx' => ['type' => 'index', 'columns' => ['legal_codes_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_legal_codes_has_city_offices_city_offices1' => ['type' => 'foreign', 'columns' => ['city_offices_id'], 'references' => ['city_offices', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_legal_codes_has_city_offices_legal_codes1' => ['type' => 'foreign', 'columns' => ['legal_codes_id'], 'references' => ['legal_codes', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'legal_codes_id' => 1,
            'city_offices_id' => 1,
            'value' => 1
        ],
    ];
}
