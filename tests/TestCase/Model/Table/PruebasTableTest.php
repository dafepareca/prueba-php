<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PruebasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PruebasTable Test Case
 */
class PruebasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PruebasTable
     */
    public $Pruebas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pruebas',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.user_statuses',
        'app.customers',
        'app.customer_type_identifications',
        'app.obligations',
        'app.type_obligations',
        'app.charges',
        'app.history_customers',
        'app.history_statuses',
        'app.history_details',
        'app.history_normalizations',
        'app.queries_customers',
        'app.access_groups',
        'app.ips_groups',
        'app.access_logs',
        'app.logouts_types',
        'app.audits',
        'app.audit_deltas',
        'app.business',
        'app.customer_typeentifications'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Pruebas') ? [] : ['className' => PruebasTable::class];
        $this->Pruebas = TableRegistry::get('Pruebas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pruebas);

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
