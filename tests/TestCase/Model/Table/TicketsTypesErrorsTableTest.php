<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TicketsTypesErrorsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TicketsTypesErrorsTable Test Case
 */
class TicketsTypesErrorsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TicketsTypesErrorsTable
     */
    public $TicketsTypesErrors;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tickets_types_errors'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TicketsTypesErrors') ? [] : ['className' => TicketsTypesErrorsTable::class];
        $this->TicketsTypesErrors = TableRegistry::get('TicketsTypesErrors', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TicketsTypesErrors);

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
