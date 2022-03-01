<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccessGroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccessGroupsTable Test Case
 */
class AccessGroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AccessGroupsTable
     */
    public $AccessGroups;

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
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AccessGroups') ? [] : ['className' => 'App\Model\Table\AccessGroupsTable'];
        $this->AccessGroups = TableRegistry::get('AccessGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AccessGroups);

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
