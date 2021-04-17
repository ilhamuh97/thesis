<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompletionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompletionsTable Test Case
 */
class CompletionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CompletionsTable
     */
    public $Completions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Completions',
        'app.Products',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Completions') ? [] : ['className' => CompletionsTable::class];
        $this->Completions = TableRegistry::getTableLocator()->get('Completions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Completions);

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
