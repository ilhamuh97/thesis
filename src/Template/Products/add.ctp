<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('actions', [
        'type' => 'Product',
        'typePlural' => 'Products'
    ]); ?>
</nav>
<div class="products form large-9 medium-8 columns content">
    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><?= __('Add Product') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('brand');
            echo $this->Form->control('category');
            echo $this->Form->control('localized_aspects');
            echo $this->Form->control('completions._ids', ['options' => $completions]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
