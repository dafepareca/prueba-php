<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomersBusinessUnitsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomersBusinessUnitsTable Test Case
 */
class CustomersBusinessUnitsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomersBusinessUnitsTable
     */
    public $CustomersBusinessUnits;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.customers_business_units',
        'app.business_units',
        'app.campaigns',
        'app.users',
        'app.roles',
        'app.users_business_units',
        'app.customers',
        'app.access_groups',
        'app.ips_groups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CustomersBusinessUnits') ? [] : ['className' => 'App\Model\Table\CustomersBusinessUnitsTable'];
        $this->CustomersBusinessUnits = TableRegistry::get('CustomersBusinessUnits', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomersBusinessUnits);

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
