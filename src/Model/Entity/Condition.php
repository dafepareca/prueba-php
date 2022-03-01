<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Condition Entity
 *
 * @property int $id
 * @property int $type_condition_id
 * @property int $condition_id
 * @property string $operator
 * @property string $value
 * @property string $value_2
 * @property string $compare
 * @property int $sort
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\TypesCondition $types_condition
 */
class Condition extends Entity
{

    var $value_2 = '';
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
        '*' => true,
        'id' => false
    ];
}
