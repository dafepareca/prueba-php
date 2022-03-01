<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HistoryPunishedPortfolio Entity
 *
 * @property int $id
 * @property float $fee
 * @property float $rate
 * @property int $term
 * @property bool $selected
 * @property int $history_customer_id
 * @property float $initial_condonation
 * @property float $value_initial_condonation
 * @property float $end_condonation
 * @property float $value_end_condonation
 * @property float $initial_payment
 *
 * @property \App\Model\Entity\HistoryCustomer $history_customer
 */
class HistoryPunishedPortfolio extends Entity
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
