<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Completion Entity
 *
 * @property int $id
 * @property string|null $title
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $type
 *
 * @property \App\Model\Entity\Product[] $products
 * @property \App\Model\Entity\Suggestion[] $suggestions
 */
class Completion extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'created' => true,
        'modified' => true,
        'type' => true,
        'products' => true,
        'suggestions' => true,
    ];
}
