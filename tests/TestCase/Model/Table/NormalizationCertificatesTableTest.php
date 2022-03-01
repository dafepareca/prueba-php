<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NormalizationCertificatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NormalizationCertificatesTable Test Case
 */
class NormalizationCertificatesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NormalizationCertificatesTable
     */
    public $NormalizationCertificates;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.normalization_certificates',
        'app.normalizations',
        'app.certificates',
        'app.normalizations_has_certificates'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('NormalizationCertificates') ? [] : ['className' => NormalizationCertificatesTable::class];
        $this->NormalizationCertificates = TableRegistry::get('NormalizationCertificates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NormalizationCertificates);

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
