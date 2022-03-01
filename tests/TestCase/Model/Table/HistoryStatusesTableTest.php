<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoryStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoryStatusesTable Test Case
 */
class HistoryStatusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoryStatusesTable
     */
    public $HistoryStatuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.history_statuses',
        'app.history_customers',
        'app.customers',
        'app.customer_type_identifications',
        'app.obligations',
        'app.type_obligations',
        'app.history_detail',
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
        $config = TableRegistry::exists('HistoryStatuses') ? [] : ['className' => HistoryStatusesTable::class];
        $this->HistoryStatuses = TableRegistry::get('HistoryStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HistoryStatuses);

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
