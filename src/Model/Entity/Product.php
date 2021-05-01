<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property string $title
 * @property string|null $category_flow
 * @property int|null $category_id
 * @property string|null $inferred_localized_aspects
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\Completion[] $completions
 * @property \App\Model\Entity\ProductType[] $product_types
 */
class Product extends Entity
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
        'inferred_localized_aspects' => true,
        'created' => true,
        'modified' => true,
        'completions' => true,
        'completion_title' => true,
        'product_types' => true,
        'product_type_titles' => true,
    ];
}
