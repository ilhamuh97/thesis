<td class="actions">
    <?= $this->Html->link(__('View'), ['controller' => 'Products', 'action' => 'view', $typeId]) ?>
    <?= $this->Html->link(__('Edit'), ['controller' => 'Products', 'action' => 'edit', $typeId]) ?>
    <?= $this->Html->link(__('Generate'), ['controller' => 'Products', 'action' => 'generate', $typeId]) ?>
    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Products', 'action' => 'delete', $typeId], ['confirm' => __('Are you sure you want to delete # {0}?', $typeId)]) ?>
</td>
