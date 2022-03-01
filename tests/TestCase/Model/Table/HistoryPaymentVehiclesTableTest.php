<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HistoryPaymentVehiclesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HistoryPaymentVehiclesTable Test Case
 */
class HistoryPaymentVehiclesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HistoryPaymentVehiclesTable
     */
    public $HistoryPaymentVehicles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.history_payment_vehicles',
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
        'app.history_punished_portfolios'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('HistoryPaymentVehicles') ? [] : ['className' => HistoryPaymentVehiclesTable::class];
        $this->HistoryPaymentVehicles = TableRegistry::get('HistoryPaymentVehicles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HistoryPaymentVehicles);

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
