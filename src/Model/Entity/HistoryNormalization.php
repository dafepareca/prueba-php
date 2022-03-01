<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HistoryNormalization Entity
 *
 * @property int $id
 * @property float $fee
 * @property float $rate
 * @property int $term
 * @property int $history_customer_id
 *
 * @property \App\Model\Entity\HistoryCustomer $history_customer
 */
class HistoryNormalization extends Entity
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
