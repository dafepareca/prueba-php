<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserStatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserStatusesTable Test Case
 */
class UserStatusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserStatusesTable
     */
    public $UserStatuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_statuses',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.customers',
        'app.campaigns',
        'app.business_units',
        'app.users_business_units',
        'app.customers_business_units',
        'app.dashboards',
        'app.dashboards_urls',
        'app.documents',
        'app.campaigns_users',
        'app.access_groups',
        'app.ips_groups',
        'app.access_logs',
        'app.access_activities',
        'app.access_types_activities'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UserStatuses') ? [] : ['className' => 'App\Model\Table\UserStatusesTable'];
        $this->UserStatuses = TableRegistry::get('UserStatuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserStatuses);

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
