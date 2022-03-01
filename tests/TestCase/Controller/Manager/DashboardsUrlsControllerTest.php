<?php
namespace App\Test\TestCase\Controller\Manager;

use App\Controller\Manager\DashboardsUrlsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Manager\DashboardsUrlsController Test Case
 */
class DashboardsUrlsControllerTest extends IntegrationTestCase
{

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
        'app.campaigns_users',
        'app.busines_units',
        'app.documents'
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
