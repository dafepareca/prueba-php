<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LogsFilesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LogsFilesTable Test Case
 */
class LogsFilesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LogsFilesTable
     */
    public $LogsFiles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.logs_files'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LogsFiles') ? [] : ['className' => LogsFilesTable::class];
        $this->LogsFiles = TableRegistry::get('LogsFiles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LogsFiles);

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
