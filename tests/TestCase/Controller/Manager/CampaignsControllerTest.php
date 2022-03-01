<?php
namespace App\Test\TestCase\Controller\Manager;

use App\Controller\Manager\CampaignsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Manager\CampaignsController Test Case
 */
class CampaignsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.campaigns',
        'app.business_units',
        'app.users',
        'app.roles',
        'app.users_business_units',
        'app.customers',
        'app.access_groups',
        'app.ips_groups',
        'app.campaigns_users',
        'app.dashboards',
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
