<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdjustedObligationsDetailTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AdjustedObligationsDetailTable Test Case
 */
class AdjustedObligationsDetailTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AdjustedObligationsDetailTable
     */
    public $AdjustedObligationsDetail;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.adjusted_obligations_detail',
        'app.adjusted_obligations',
        'app.adjusted_obligations_details'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AdjustedObligationsDetail') ? [] : ['className' => AdjustedObligationsDetailTable::class];
        $this->AdjustedObligationsDetail = TableRegistry::get('AdjustedObligationsDetail', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AdjustedObligationsDetail);

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
