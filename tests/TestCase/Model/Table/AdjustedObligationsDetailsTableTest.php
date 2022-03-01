<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdjustedObligationsDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AdjustedObligationsDetailsTable Test Case
 */
class AdjustedObligationsDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AdjustedObligationsDetailsTable
     */
    public $AdjustedObligationsDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.adjusted_obligations_details',
        'app.adjusted_obligations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AdjustedObligationsDetails') ? [] : ['className' => AdjustedObligationsDetailsTable::class];
        $this->AdjustedObligationsDetails = TableRegistry::get('AdjustedObligationsDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AdjustedObligationsDetails);

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
