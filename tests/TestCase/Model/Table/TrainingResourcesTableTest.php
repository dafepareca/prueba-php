<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TrainingResourcesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TrainingResourcesTable Test Case
 */
class TrainingResourcesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TrainingResourcesTable
     */
    public $TrainingResources;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.training_resources',
        'app.trainings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TrainingResources') ? [] : ['className' => TrainingResourcesTable::class];
        $this->TrainingResources = TableRegistry::get('TrainingResources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TrainingResources);

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
