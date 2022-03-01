<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccessTypesActivitiesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccessTypesActivitiesTable Test Case
 */
class AccessTypesActivitiesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AccessTypesActivitiesTable
     */
    public $AccessTypesActivities;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.access_types_activities'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AccessTypesActivities') ? [] : ['className' => 'App\Model\Table\AccessTypesActivitiesTable'];
        $this->AccessTypesActivities = TableRegistry::get('AccessTypesActivities', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AccessTypesActivities);

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
