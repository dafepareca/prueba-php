<?php
namespace App\Test\TestCase\Controller\Coordinador;

use App\Controller\Coordinador\CommitteesController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\Coordinador\CommitteesController Test Case
 */
class CommitteesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.committees',
        'app.users',
        'app.attachments',
        'app.roles',
        'app.user_statuses',
        'app.customers',
        'app.customer_type_identifications',
        'app.obligations',
        'app.type_obligations',
        'app.history_customers',
        'app.history_statuses',
        'app.history_details',
        'app.history_normalizations',
        'app.access_groups',
        'app.ips_groups',
        'app.access_logs',
        'app.logouts_types',
        'app.audits',
        'app.audit_deltas'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
