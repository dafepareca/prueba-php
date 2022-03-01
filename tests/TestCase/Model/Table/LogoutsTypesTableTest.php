<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LogoutsTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LogoutsTypesTable Test Case
 */
class LogoutsTypesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LogoutsTypesTable
     */
    public $LogoutsTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.logouts_types'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LogoutsTypes') ? [] : ['className' => 'App\Model\Table\LogoutsTypesTable'];
        $this->LogoutsTypes = TableRegistry::get('LogoutsTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LogoutsTypes);

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
