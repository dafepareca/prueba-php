<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HistoryDetail Entity
 *
 * @property int $id
 * @property string $obligation
 * @property string $strategy
 * @property string $type_strategy
 * @property int $term
 * @property string $new _fee
 * @property bool $selected
 * @property int $type_obligation_id
 * @property int $history_customer_id
 * @property string $maskobligation
 *
 * @property \App\Model\Entity\TypeObligation $type_obligation
 * @property \App\Model\Entity\HistoryCustomer $history_customer
 */
class HistoryDetail extends Entity
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

    var $maskobligation = '';

    /*protected function _getObligation($obligation){
        if (strlen($obligation) > 0) {
            return substr($obligation, 0, 4) . '*****' . substr($obligation, -4);
        }
    }*/
}
