<?php
namespace App\Test\TestCase\Controller\Admin;

use App\Controller\Admin\AccessGroupsController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Admin\AccessGroupsController Test Case
 */
class AccessGroupsControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.access_groups',
        'app.ips_groups',
        'app.users',
        'app.roles',
        'app.business_units',
        'app.campaigns',
        'app.users_business_units',
        'app.customers'
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
