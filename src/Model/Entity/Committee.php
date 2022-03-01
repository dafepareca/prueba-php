<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Committee Entity
 *
 * @property int $id
 * @property int $coordinator_id
 * @property int $history_id
 * @property \Cake\I18n\FrozenTime $create
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Coordinator $coordinator
 * @property \App\Model\Entity\User $asesor
 * @property \App\Model\Entity\HistoryCustomer $history_customer
 */
class Committee extends Entity
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
        '*' => true,
        'id' => false
    ];
}
