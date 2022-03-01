<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoryCustomersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoryCustomersTable Test Case
 */
class HistoryCustomersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoryCustomersTable
     */
    public $HistoryCustomers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.history_customers',
        'app.customers',
        'app.customer_type_identifications',
        'app.obligations',
        'app.type_obligations',
        'app.history_statuses',
        'app.history_details',
        'app.history_normalizations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('HistoryCustomers') ? [] : ['className' => HistoryCustomersTable::class];
        $this->HistoryCustomers = TableRegistry::get('HistoryCustomers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HistoryCustomers);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
