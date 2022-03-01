<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CampaignsInternalCustomersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CampaignsInternalCustomersTable Test Case
 */
class CampaignsInternalCustomersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CampaignsInternalCustomersTable
     */
    public $CampaignsInternalCustomers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.campaigns_internal_customers',
        'app.campaigns',
        'app.business_units',
        'app.users_business_units',
        'app.users',
        'app.roles',
        'app.customers',
        'app.customers_business_units',
        'app.access_groups',
        'app.ips_groups',
        'app.dashboards',
        'app.dashboards_urls',
        'app.documents',
        'app.campaigns_users',
        'app.internal_customers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CampaignsInternalCustomers') ? [] : ['className' => 'App\Model\Table\CampaignsInternalCustomersTable'];
        $this->CampaignsInternalCustomers = TableRegistry::get('CampaignsInternalCustomers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CampaignsInternalCustomers);

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
