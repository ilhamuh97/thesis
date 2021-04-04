<ul class="side-nav">
    <li class="heading"><?= __('Actions') ?></li>
    <li><?= $this->Html->link(__('New '. $type), ['action' => 'add']) ?></li>
    <li><?= $this->Html->link(__('List '. $typePlural), ['action' => 'index']) ?></li>
    <li><?= $this->Html->link(__('Edit '. $type), ['action' => 'edit', $typeId]) ?> </li>
    <li><?= $this->Form->postLink(__('Delete '. $type), ['action' => 'delete', $typeId], ['confirm' => __('Are you sure you want to delete # {0}?', $typeId)]) ?> </li>
 </ul>
 