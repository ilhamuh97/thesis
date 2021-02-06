<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Completion $completion
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Completion'), ['action' => 'edit', $completion->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Completion'), ['action' => 'delete', $completion->id], ['confirm' => __('Are you sure you want to delete # {0}?', $completion->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Completions'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Completion'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Suggestions'), ['controller' => 'Suggestions', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Suggestion'), ['controller' => 'Suggestions', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="completions view large-9 medium-8 columns content">
    <h3><?= h($completion->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($completion->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($completion->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($completion->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($completion->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Suggestions') ?></h4>
        <?php if (!empty($completion->suggestions)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Title') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($completion->suggestions as $suggestions): ?>
            <tr>
                <td><?= h($suggestions->id) ?></td>
                <td><?= h($suggestions->title) ?></td>
                <td><?= h($suggestions->created) ?></td>
                <td><?= h($suggestions->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Suggestions', 'action' => 'view', $suggestions->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Suggestions', 'action' => 'edit', $suggestions->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Suggestions', 'action' => 'delete', $suggestions->id], ['confirm' => __('Are you sure you want to delete # {0}?', $suggestions->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
