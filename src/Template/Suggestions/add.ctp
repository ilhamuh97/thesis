<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Suggestion $suggestion
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Suggestions'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Completions'), ['controller' => 'Completions', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Completion'), ['controller' => 'Completions', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="suggestions form large-9 medium-8 columns content">
    <?= $this->Form->create($suggestion) ?>
    <fieldset>
        <legend><?= __('Add Suggestion') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('completions._ids', ['options' => $completions]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
