<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdjustedObligationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AdjustedObligationsTable Test Case
 */
class AdjustedObligationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AdjustedObligationsTable
     */
    public $AdjustedObligations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        $config = TableRegistry::exists('AdjustedObligations') ? [] : ['className' => AdjustedObligationsTable::class];
        $this->AdjustedObligations = TableRegistry::get('AdjustedObligations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AdjustedObligations);

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
