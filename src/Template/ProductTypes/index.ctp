<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductType[]|\Cake\Collection\CollectionInterface $productTypes
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('Actions/actionsDefault', [
        'type' => 'ProductType',
        'typePlural' => 'ProductTypes'
    ]); ?>
</nav>
<div class="productTypes index large-9 medium-8 columns content">
    <h3><?= __('Product Types') ?></h3>
    <?= $this->Form->create($productTypes, ['url' => ['action' => 'index'], 'type' => 'get']); ?>
    <fieldset>
        <legend><?= __('Search') ?></legend>
        <?php
            echo $this->Form->control('input_id', ['label' => 'Id', 'type'=>'text']);
            echo $this->Form->control('input_title', ['label' => 'Title']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productTypes as $productType): ?>
            <tr>
                <td><?= $this->Number->format($productType->id) ?></td>
                <td><?= h($productType->title) ?></td>
                <td><?= h($productType->created) ?></td>
                <td><?= h($productType->modified) ?></td>
                <?= $this->element('Actions/actionsUnitDefault', [
                    'typeId' => $productType->id,
                    'controllerName' => 'ProductTypes'
                ]); ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
