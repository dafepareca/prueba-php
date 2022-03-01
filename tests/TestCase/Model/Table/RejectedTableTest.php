<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RejectedTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RejectedTable Test Case
 */
class RejectedTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RejectedTable
     */
    public $Rejected;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.rejected',
        'app.type_rejecteds',
        'app.history_customers',
        'app.customers',
        'app.customer_type_identifications',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.user_statuses',
        'app.access_groups',
        'app.ips_groups',
        'app.access_logs',
        'app.logouts_types',
        'app.audits',
        'app.audit_deltas',
        'app.business',
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
        $config = TableRegistry::exists('Rejected') ? [] : ['className' => RejectedTable::class];
        $this->Rejected = TableRegistry::get('Rejected', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Rejected);

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
