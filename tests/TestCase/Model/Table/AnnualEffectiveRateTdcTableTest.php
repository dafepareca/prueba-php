<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnnualEffectiveRateTdcTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnnualEffectiveRateTdcTable Test Case
 */
class AnnualEffectiveRateTdcTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AnnualEffectiveRateTdcTable
     */
    public $AnnualEffectiveRateTdc;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.annual_effective_rate_tdc'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AnnualEffectiveRateTdc') ? [] : ['className' => AnnualEffectiveRateTdcTable::class];
        $this->AnnualEffectiveRateTdc = TableRegistry::get('AnnualEffectiveRateTdc', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AnnualEffectiveRateTdc);

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
