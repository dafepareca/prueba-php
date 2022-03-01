<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TicketsTypesTitlesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TicketsTypesTitlesTable Test Case
 */
class TicketsTypesTitlesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TicketsTypesTitlesTable
     */
    public $TicketsTypesTitles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tickets_types_titles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TicketsTypesTitles') ? [] : ['className' => TicketsTypesTitlesTable::class];
        $this->TicketsTypesTitles = TableRegistry::get('TicketsTypesTitles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TicketsTypesTitles);

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
