<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProductCodesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProductCodesTable Test Case
 */
class ProductCodesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProductCodesTable
     */
    public $ProductCodes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.product_codes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ProductCodes') ? [] : ['className' => ProductCodesTable::class];
        $this->ProductCodes = TableRegistry::get('ProductCodes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProductCodes);

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
