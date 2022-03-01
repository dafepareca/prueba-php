<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\IpsGroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\IpsGroupsTable Test Case
 */
class IpsGroupsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\IpsGroupsTable
     */
    public $IpsGroups;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ips_groups',
        'app.access_groups',
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
        $config = TableRegistry::exists('IpsGroups') ? [] : ['className' => 'App\Model\Table\IpsGroupsTable'];
        $this->IpsGroups = TableRegistry::get('IpsGroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->IpsGroups);

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
