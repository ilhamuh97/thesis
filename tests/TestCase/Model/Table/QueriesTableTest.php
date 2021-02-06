<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QueriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QueriesTable Test Case
 */
class QueriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\QueriesTable
     */
    public $Queries;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Queries',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Queries') ? [] : ['className' => QueriesTable::class];
        $this->Queries = TableRegistry::getTableLocator()->get('Queries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Queries);

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
