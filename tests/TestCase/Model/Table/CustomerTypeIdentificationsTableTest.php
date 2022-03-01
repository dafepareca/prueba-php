<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CustomerTypeIdentificationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CustomerTypeIdentificationsTable Test Case
 */
class CustomerTypeIdentificationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CustomerTypeIdentificationsTable
     */
    public $CustomerTypeIdentifications;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.customer_type_identifications',
        'app.customers',
        'app.obligations',
        'app.type_obligations',
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
        $config = TableRegistry::exists('CustomerTypeIdentifications') ? [] : ['className' => CustomerTypeIdentificationsTable::class];
        $this->CustomerTypeIdentifications = TableRegistry::get('CustomerTypeIdentifications', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CustomerTypeIdentifications);

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
