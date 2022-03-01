<?php
namespace App\Test\TestCase\Model\Behavior;

use App\Model\Behavior\BeforeDeleteBehavior;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Behavior\BeforeDeleteBehavior Test Case
 */
class BeforeDeleteBehaviorTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Behavior\BeforeDeleteBehavior
     */
    public $BeforeDelete;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->BeforeDelete = new BeforeDeleteBehavior();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BeforeDelete);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
