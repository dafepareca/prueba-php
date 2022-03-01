<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NegotiationReasonTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NegotiationReasonTable Test Case
 */
class NegotiationReasonTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NegotiationReasonTable
     */
    public $NegotiationReason;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.negotiation_reason',
        'app.history_customers',
        'app.customers',
        'app.customer_type_identifications',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.user_statuses',
        'app.access_groups',
        'app.ips_groups',
        'app.business',
        'app.access_logs',
        'app.logouts_types',
        'app.audits',
        'app.audit_deltas',
        'app.obligations',
        'app.type_obligations',
        'app.charges',
        'app.queries_customers',
        'app.history_statuses',
        'app.history_details',
        'app.history_normalizations',
        'app.history_punished_portfolios',
        'app.history_payment_vehicles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NegotiationReason') ? [] : ['className' => NegotiationReasonTable::class];
        $this->NegotiationReason = TableRegistry::get('NegotiationReason', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NegotiationReason);

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
