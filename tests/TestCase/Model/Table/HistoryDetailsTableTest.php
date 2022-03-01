<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoryDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoryDetailsTable Test Case
 */
class HistoryDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoryDetailsTable
     */
    public $HistoryDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.history_details',
        'app.type_obligations',
        'app.obligations',
        'app.customers',
        'app.customer_type_identifications',
        'app.history_customers',
        'app.history_statuses',
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
        $config = TableRegistry::exists('HistoryDetails') ? [] : ['className' => HistoryDetailsTable::class];
        $this->HistoryDetails = TableRegistry::get('HistoryDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HistoryDetails);

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
