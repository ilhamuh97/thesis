<?php
namespace App\Model\Entity;

use Cake\Collection\Collection;
use Cake\ORM\Entity;

/**
 * Completion Entity
 *
 * @property int $id
 * @property string $title
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
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
        'suggestions' => true,
        'suggestion_string' => true,
    ];

    protected function _getSuggestionString()
    {
        if (isset($this->_properties['suggestion_string'])) {
            return $this->_properties['suggestion_string'];
        }
        if (empty($this->suggestions)) {
            return '';
        }
        $suggestions = new Collection($this->suggestions);
        $str = $suggestions->reduce(function ($string, $suggestion) {
            return $string . $suggestion->title . ', ';
        }, '');
        return trim($str, ', ');
    }
}
