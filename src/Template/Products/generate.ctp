<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="products form large-9 medium-8 columns content">
    <h3><?= h($readable_product->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($readable_product->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Brand') ?></th>
            <td><?= h($readable_product->brand) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= h(implode(', ', $readable_product->categories)) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($readable_product->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($readable_product->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($readable_product->modified) ?></td>
        </tr>
    </table>
    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><?= __('Generate Suggestions') ?></legend>
        <?php
            echo $this->Form->control('product type', ['required' => true]);
            echo $this->Form->control('selected_attributes._ids', ['options' => $readable_product->attributes]);
            echo $this->Form->control('brand', ['required' => false, 'value'=>$readable_product->brand]);
            echo $this->Form->control('categories._ids', ['required' => false, 'options' => $readable_product->categories]);
            echo $this->Form->control('completions._ids', ['options' => $completions]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>