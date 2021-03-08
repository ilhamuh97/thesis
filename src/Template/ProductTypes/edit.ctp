<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductType $productType
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
    __('Delete'),
    ['action' => 'delete', $productType->id],
    ['confirm' => __('Are you sure you want to delete # {0}?', $productType->id)]
)
        ?></li>
        <li><?= $this->Html->link(__('List Product Types'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="productTypes form large-9 medium-8 columns content">
    <?= $this->Form->create($productType) ?>
    <fieldset>
        <legend><?= __('Edit Product Type') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('products._ids', ['options' => $products]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
