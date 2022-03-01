<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TypeObligationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TypeObligationsTable Test Case
 */
class TypeObligationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TypeObligationsTable
     */
    public $TypeObligations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.type_obligations',
        'app.obligations',
        'app.customers',
        'app.customer_type_identifications',
        'app.history_customers',
        'app.history_statuses',
        'app.history_details',
        'app.history_normalizations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TypeObligations') ? [] : ['className' => TypeObligationsTable::class];
        $this->TypeObligations = TableRegistry::get('TypeObligations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TypeObligations);

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
