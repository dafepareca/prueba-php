<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HistoryPaymentVehicle Entity
 *
 * @property int $id
 * @property float $total_payment
 * @property float $total_payment_expert
 * @property float $customer_offer
 * @property float $condonation
 * @property string $value_condonation
 * @property int $history_customer_id
 * @property float $value_parking
 * @property float $value_subpoena
 * @property float $value_taxes
 * @property float $value_others
 * @property float $value_valorization
 * @property float $type_valorization
 *
 * @property \App\Model\Entity\HistoryCustomer $history_customer
 */
class HistoryPaymentVehicle extends Entity
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
