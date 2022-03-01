<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CityOfficesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CityOfficesTable Test Case
 */
class CityOfficesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CityOfficesTable
     */
    public $CityOffices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.city_offices',
        'app.offices'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CityOffices') ? [] : ['className' => CityOfficesTable::class];
        $this->CityOffices = TableRegistry::get('CityOffices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CityOffices);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
