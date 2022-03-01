<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TypeRejectedTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TypeRejectedTable Test Case
 */
class TypeRejectedTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TypeRejectedTable
     */
    public $TypeRejected;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.type_rejected'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TypeRejected') ? [] : ['className' => TypeRejectedTable::class];
        $this->TypeRejected = TableRegistry::get('TypeRejected', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TypeRejected);

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
