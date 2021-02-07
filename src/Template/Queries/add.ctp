<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $query
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <?= $this->element('actions', [
        'type' => 'Query',
        'typePlural' => 'Queries'
    ]); ?>
</nav>
<div class="queries form large-9 medium-8 columns content">
    <?= $this->Form->create($query) ?>
    <fieldset>
        <legend><?= __('Add Query') ?></legend>
        <?php
            echo $this->Form->control('title');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
