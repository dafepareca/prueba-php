<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DashboardsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DashboardsTable Test Case
 */
class DashboardsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DashboardsTable
     */
    public $Dashboards;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.dashboards',
        'app.campaigns',
        'app.business_units',
        'app.users_business_units',
        'app.users',
        'app.roles',
        'app.customers',
        'app.customers_business_units',
        'app.access_groups',
        'app.ips_groups',
        'app.busines_units',
        'app.documents',
        'app.campaigns_users',
        'app.dashboards_urls'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Dashboards') ? [] : ['className' => 'App\Model\Table\DashboardsTable'];
        $this->Dashboards = TableRegistry::get('Dashboards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Dashboards);

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
