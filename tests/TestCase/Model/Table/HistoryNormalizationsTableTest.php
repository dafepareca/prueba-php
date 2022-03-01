<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoryNormalizationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoryNormalizationsTable Test Case
 */
class HistoryNormalizationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoryNormalizationsTable
     */
    public $HistoryNormalizations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.history_normalizations',
        'app.history_customers',
        'app.customers',
        'app.customer_type_identifications',
        'app.obligations',
        'app.type_obligations',
        'app.history_statuses',
        'app.history_detail'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('HistoryNormalizations') ? [] : ['className' => HistoryNormalizationsTable::class];
        $this->HistoryNormalizations = TableRegistry::get('HistoryNormalizations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HistoryNormalizations);

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
