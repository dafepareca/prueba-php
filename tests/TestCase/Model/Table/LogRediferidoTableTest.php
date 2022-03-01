<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LogRediferidoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LogRediferidoTable Test Case
 */
class LogRediferidoTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LogRediferidoTable
     */
    public $LogRediferido;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.log_rediferido'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LogRediferido') ? [] : ['className' => LogRediferidoTable::class];
        $this->LogRediferido = TableRegistry::get('LogRediferido', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LogRediferido);

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
