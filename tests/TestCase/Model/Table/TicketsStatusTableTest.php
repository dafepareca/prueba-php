<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TicketsStatusTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TicketsStatusTable Test Case
 */
class TicketsStatusTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TicketsStatusTable
     */
    public $TicketsStatus;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tickets_status'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TicketsStatus') ? [] : ['className' => TicketsStatusTable::class];
        $this->TicketsStatus = TableRegistry::get('TicketsStatus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TicketsStatus);

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
