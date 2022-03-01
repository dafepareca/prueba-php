<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StateReasonsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StateReasonsTable Test Case
 */
class StateReasonsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StateReasonsTable
     */
    public $StateReasons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.state_reasons',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.user_statuses',
        'app.customers',
        'app.customer_type_identifications',
        'app.obligations',
        'app.type_obligations',
        'app.history_customers',
        'app.history_statuses',
        'app.history_details',
        'app.history_normalizations',
        'app.access_groups',
        'app.ips_groups',
        'app.access_logs',
        'app.logouts_types',
        'app.audits',
        'app.audit_deltas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('StateReasons') ? [] : ['className' => StateReasonsTable::class];
        $this->StateReasons = TableRegistry::get('StateReasons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->StateReasons);

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
