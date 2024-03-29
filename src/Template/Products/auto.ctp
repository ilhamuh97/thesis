<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product[]|\Cake\Collection\CollectionInterface $products
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('Actions/actionsDefault', [
        'type' => 'Product',
        'typePlural' => 'Products'
    ]); ?>
</nav>
<div class="products index large-9 medium-8 columns content">
    <h3><?= __('Multiple Completion Generator') ?></h3>
    <?= $this->Form->create($products) ?>
    <fieldset>
        <legend><?= __('Generate Completions') ?></legend>
        <?php
            echo $this->Form->control('product_types._ids', ['options' => $product_types]);
            echo $this->Form->control('attributes');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    