<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Product'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Product'), ['action' => 'edit', $product->id]) ?> </li>
        <li><?= $this->Html->link(__('Generate Product'), ['action' => 'generate', $product->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]) ?> </li>
    </ul>
</nav>
<div class="products view large-9 medium-8 columns content">
    <h3><?= h($product->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($product->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Brand') ?></th>
            <td><?= h($product->brand) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= h($product->category) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Localized_Aspects') ?></th>
            <td><?= h($product->localized_aspects) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($product->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($product->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($product->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Completions') ?></h4>
        <?php if (!empty($product->completions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($product->completions as $completions): ?>
            <tr>
                <td><?= h($completions->id) ?></td>
                <td><?= h($completions->title) ?></td>
                <td><?= h($completions->created) ?></td>
                <td><?= h($completions->modified) ?></td>
                <?= $this->element('Actions/actionsUnitDefault', [
                    'typeId' => $completions->id,
                    'controllerName' => 'Completions'
                ]); ?>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
        <br>
        <h4><?= __('Related Product Types') ?></h4>
        <?php if (!empty($product->product_types)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($product->product_types as $product_type): ?>
            <tr>
                <td><?= h($product_type->id) ?></td>
                <td><?= h($product_type->title) ?></td>
                <td><?= h($product_type->created) ?></td>
                <td><?= h($product_type->modified) ?></td>
                <?= $this->element('Actions/actionsUnitDefault', [
                    'typeId' => $product_type->id,
                    'controllerName' => 'ProductTypes'
                ]); ?>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
