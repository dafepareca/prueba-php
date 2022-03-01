<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NormalizationReasonsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NormalizationReasonsTable Test Case
 */
class NormalizationReasonsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NormalizationReasonsTable
     */
    public $NormalizationReasons;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.normalization_reasons',
        'app.normalizations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NormalizationReasons') ? [] : ['className' => NormalizationReasonsTable::class];
        $this->NormalizationReasons = TableRegistry::get('NormalizationReasons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NormalizationReasons);

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
