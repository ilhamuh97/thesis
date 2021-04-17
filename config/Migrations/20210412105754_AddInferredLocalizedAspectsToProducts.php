<?php
use Migrations\AbstractMigration;

class AddInferredLocalizedAspectsToProducts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('products');
        $table->addColumn('inferred_localized_aspects', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
