<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StatusesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StatusesTable Test Case
 */
class StatusesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\StatusesTable
     */
    public $Statuses;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.statuses',
        'app.campaigns',
        'app.business_units',
        'app.users_business_units',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.user_statuses',
        'app.customers',
        'app.customers_business_units',
        'app.access_groups',
        'app.ips_groups',
        'app.access_logs',
        'app.access_activities',
        'app.access_types_activities',
        'app.dashboards',
        'app.dashboards_urls',
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
        $config = TableRegistry::exists('Statuses') ? [] : ['className' => 'App\Model\Table\StatusesTable'];
        $this->Statuses = TableRegistry::get('Statuses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Statuses);

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
