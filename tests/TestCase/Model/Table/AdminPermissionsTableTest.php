<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdminPermissionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AdminPermissionsTable Test Case
 */
class AdminPermissionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AdminPermissionsTable
     */
    public $AdminPermissions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.admin_permissions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AdminPermissions') ? [] : ['className' => AdminPermissionsTable::class];
        $this->AdminPermissions = TableRegistry::get('AdminPermissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AdminPermissions);

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
