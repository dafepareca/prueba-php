<?php
namespace App\Test\TestCase\Controller\Admin;

use App\Controller\Admin\AccessActivitiesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Admin\AccessActivitiesController Test Case
 */
class AccessActivitiesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.access_activities',
        'app.access_logs',
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
        'app.access_types_activities'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
