<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Completion $completion
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('actions', [
        'type' => 'Completion',
        'typePlural' => 'Completions'
    ]); ?>
</nav>
<div class="completions form large-9 medium-8 columns content">
    <?= $this->Form->create($completion) ?>
    <fieldset>
        <legend><?= __('Add Completion') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('suggestions._ids', ['options' => $suggestions]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
