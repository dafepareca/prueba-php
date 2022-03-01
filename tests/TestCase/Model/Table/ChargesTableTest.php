<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChargesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChargesTable Test Case
 */
class ChargesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChargesTable
     */
    public $Charges;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.charges',
        'app.customers',
        'app.customer_type_identifications',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.user_statuses',
        'app.access_groups',
        'app.ips_groups',
        'app.access_logs',
        'app.logouts_types',
        'app.audits',
        'app.audit_deltas',
        'app.obligations',
        'app.type_obligations',
        'app.history_customers',
        'app.history_statuses',
        'app.history_details',
        'app.history_normalizations',
        'app.queries_customers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Charges') ? [] : ['className' => ChargesTable::class];
        $this->Charges = TableRegistry::get('Charges', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Charges);

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
