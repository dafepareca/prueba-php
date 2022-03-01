<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LogTransactionalTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LogTransactionalTable Test Case
 */
class LogTransactionalTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LogTransactionalTable
     */
    public $LogTransactional;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.log_transactional',
        'app.logs',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.user_statuses',
        'app.customers',
        'app.customer_type_identifications',
        'app.obligations',
        'app.type_obligations',
        'app.charges',
        'app.history_customers',
        'app.history_statuses',
        'app.history_details',
        'app.history_normalizations',
        'app.history_punished_portfolios',
        'app.history_payment_vehicles',
        'app.queries_customers',
        'app.access_groups',
        'app.ips_groups',
        'app.business',
        'app.access_logs',
        'app.logouts_types',
        'app.audits',
        'app.audit_deltas',
        'app.adjusted_obligations',
        'app.adjusted_obligations_details'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LogTransactional') ? [] : ['className' => LogTransactionalTable::class];
        $this->LogTransactional = TableRegistry::get('LogTransactional', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LogTransactional);

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
