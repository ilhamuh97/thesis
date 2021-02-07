<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Suggestion[]|\Cake\Collection\CollectionInterface $suggestions
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('actions', [
        'type' => 'Suggestion',
        'typePlural' => 'Suggestions'
    ]); ?>
</nav>
<div class="suggestions index large-9 medium-8 columns content">
    <h3><?= __('Suggestions') ?></h3>
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
            <?php foreach ($suggestions as $suggestion): ?>
            <tr>
                <td><?= $this->Number->format($suggestion->id) ?></td>
                <td><?= h($suggestion->title) ?></td>
                <td><?= h($suggestion->created) ?></td>
                <td><?= h($suggestion->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $suggestion->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $suggestion->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $suggestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suggestion->id)]) ?>
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
