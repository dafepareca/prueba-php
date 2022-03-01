<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CndCodesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CndCodesTable Test Case
 */
class CndCodesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CndCodesTable
     */
    public $CndCodes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.cnd_codes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CndCodes') ? [] : ['className' => CndCodesTable::class];
        $this->CndCodes = TableRegistry::get('CndCodes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CndCodes);

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
