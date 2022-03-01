<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccessLogsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccessLogsTable Test Case
 */
class AccessLogsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AccessLogsTable
     */
    public $AccessLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.access_logs',
        'app.users',
        'app.roles',
        'app.business_units',
        'app.campaigns',
        'app.customers',
        'app.customers_business_units',
        'app.dashboards',
        'app.dashboards_urls',
        'app.documents',
        'app.campaigns_users',
        'app.users_business_units',
        'app.busines_units',
        'app.access_groups',
        'app.ips_groups',
        'app.access_activities',
        'app.models',
        'app.type_activities'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AccessLogs') ? [] : ['className' => 'App\Model\Table\AccessLogsTable'];
        $this->AccessLogs = TableRegistry::get('AccessLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AccessLogs);

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
