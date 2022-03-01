<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DashboardsUrlsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DashboardsUrlsTable Test Case
 */
class DashboardsUrlsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DashboardsUrlsTable
     */
    public $DashboardsUrls;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.dashboards_urls',
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
        'app.campaigns_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DashboardsUrls') ? [] : ['className' => 'App\Model\Table\DashboardsUrlsTable'];
        $this->DashboardsUrls = TableRegistry::get('DashboardsUrls', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DashboardsUrls);

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
