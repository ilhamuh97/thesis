<td class="actions">
    <?= $this->Html->link(__('View'), ['controller' => $controllerName, 'action' => 'view', $typeId]) ?>
    <?= $this->Html->link(__('Edit'), ['controller' => $controllerName, 'action' => 'edit', $typeId]) ?>
    <?= $this->Form->postLink(__('Delete'), ['controller' => $controllerName, 'action' => 'delete', $typeId], ['confirm' => __('Are you sure you want to delete # {0}?', $typeId)]) ?>
</td>
