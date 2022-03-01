<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CampaignsUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CampaignsUsersTable Test Case
 */
class CampaignsUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CampaignsUsersTable
     */
    public $CampaignsUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.campaigns_users',
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
        'app.documents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CampaignsUsers') ? [] : ['className' => 'App\Model\Table\CampaignsUsersTable'];
        $this->CampaignsUsers = TableRegistry::get('CampaignsUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CampaignsUsers);

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
