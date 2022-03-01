<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SettingCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SettingCategoriesTable Test Case
 */
class SettingCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SettingCategoriesTable
     */
    public $SettingCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.setting_categories',
        'app.settings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SettingCategories') ? [] : ['className' => 'App\Model\Table\SettingCategoriesTable'];
        $this->SettingCategories = TableRegistry::get('SettingCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SettingCategories);

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
