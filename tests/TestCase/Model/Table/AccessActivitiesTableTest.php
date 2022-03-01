<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccessActivitiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccessActivitiesTable Test Case
 */
class AccessActivitiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AccessActivitiesTable
     */
    public $AccessActivities;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.access_activities',
        'app.models',
        'app.access_logs',
        'app.type_activities'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AccessActivities') ? [] : ['className' => 'App\Model\Table\AccessActivitiesTable'];
        $this->AccessActivities = TableRegistry::get('AccessActivities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AccessActivities);

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
