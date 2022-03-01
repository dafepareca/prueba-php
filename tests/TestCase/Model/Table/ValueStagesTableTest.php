<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ValueStagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ValueStagesTable Test Case
 */
class ValueStagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ValueStagesTable
     */
    public $ValueStages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.value_stages',
        'app.legal_codes',
        'app.city_offices',
        'app.offices',
        'app.schedules'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ValueStages') ? [] : ['className' => ValueStagesTable::class];
        $this->ValueStages = TableRegistry::get('ValueStages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ValueStages);

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
