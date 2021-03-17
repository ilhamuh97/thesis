<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
    __('Delete'),
    ['action' => 'delete', $product->id],
    ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]
)
        ?></li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="products form large-9 medium-8 columns content">
    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><?= __('Edit Product') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('brand');
            echo $this->Form->control('category');
            echo $this->Form->control('localized_aspects');
            echo $this->Form->control('product_types._ids', ['options' => $product_types]);
            echo $this->Form->control('completions._ids', ['options' => $completions]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
