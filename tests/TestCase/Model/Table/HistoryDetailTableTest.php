<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoryDetailTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoryDetailTable Test Case
 */
class HistoryDetailTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoryDetailTable
     */
    public $HistoryDetail;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.history_detail',
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
        $config = TableRegistry::exists('HistoryDetail') ? [] : ['className' => HistoryDetailTable::class];
        $this->HistoryDetail = TableRegistry::get('HistoryDetail', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HistoryDetail);

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
