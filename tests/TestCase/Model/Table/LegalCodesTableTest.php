<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LegalCodesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LegalCodesTable Test Case
 */
class LegalCodesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LegalCodesTable
     */
    public $LegalCodes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.legal_codes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LegalCodes') ? [] : ['className' => LegalCodesTable::class];
        $this->LegalCodes = TableRegistry::get('LegalCodes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LegalCodes);

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
