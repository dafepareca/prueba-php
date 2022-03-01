<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ModalMessageTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ModalMessageTable Test Case
 */
class ModalMessageTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ModalMessageTable
     */
    public $ModalMessage;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.modal_message'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ModalMessage') ? [] : ['className' => ModalMessageTable::class];
        $this->ModalMessage = TableRegistry::get('ModalMessage', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ModalMessage);

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
