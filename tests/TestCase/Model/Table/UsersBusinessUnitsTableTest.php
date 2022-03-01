<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersBusinessUnitsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersBusinessUnitsTable Test Case
 */
class UsersBusinessUnitsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersBusinessUnitsTable
     */
    public $UsersBusinessUnits;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users_business_units',
        'app.users',
        'app.roles',
        'app.business_units',
        'app.campaigns',
        'app.customers',
        'app.customers_business_units',
        'app.dashboards',
        'app.documents',
        'app.campaigns_users',
        'app.access_groups',
        'app.ips_groups',
        'app.busines_units'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UsersBusinessUnits') ? [] : ['className' => 'App\Model\Table\UsersBusinessUnitsTable'];
        $this->UsersBusinessUnits = TableRegistry::get('UsersBusinessUnits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsersBusinessUnits);

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
