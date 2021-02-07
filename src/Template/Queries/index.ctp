<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface[]|\Cake\Collection\CollectionInterface $queries
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('actions', [
        'type' => 'Query',
        'typePlural' => 'Queries'
    ]); ?>
</nav>
<div class="queries index large-9 medium-8 columns content">
    <h3><?= __('Queries') ?></h3>
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
            <?php foreach ($queries as $query): ?>
            <tr>
                <td><?= $this->Number->format($query->id) ?></td>
                <td><?= h($query->title) ?></td>
                <td><?= h($query->created) ?></td>
                <td><?= h($query->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $query->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $query->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $query->id], ['confirm' => __('Are you sure you want to delete # {0}?', $query->id)]) ?>
                </td>
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
