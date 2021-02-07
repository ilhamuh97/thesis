<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Suggestion $suggestion
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Suggestion'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Suggestions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Edit Suggestion'), ['action' => 'edit', $suggestion->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Suggestion'), ['action' => 'delete', $suggestion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suggestion->id)]) ?> </li>
    </ul>
</nav>
<div class="suggestions view large-9 medium-8 columns content">
    <h3><?= h($suggestion->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($suggestion->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($suggestion->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($suggestion->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($suggestion->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Completions') ?></h4>
        <?php if (!empty($suggestion->completions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($suggestion->completions as $completions): ?>
            <tr>
                <td><?= h($completions->id) ?></td>
                <td><?= h($completions->title) ?></td>
                <td><?= h($completions->created) ?></td>
                <td><?= h($completions->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Completions', 'action' => 'view', $completions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Completions', 'action' => 'edit', $completions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Completions', 'action' => 'delete', $completions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $completions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
