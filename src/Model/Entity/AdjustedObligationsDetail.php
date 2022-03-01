<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AdjustedObligationsDetail Entity
 *
 * @property int $id
 * @property string $obligation
 * @property string $strategy
 * @property string $type_strategy
 * @property float $total_debt
 * @property float $annual_effective_rate
 * @property float $nominal_rate
 * @property float $monthly_rate
 * @property int $months_term
 * @property float $customer_revenue
 * @property float $customer_paid_capacity
 * @property float $previous_minimum_payment
 * @property int $initial_fee
 * @property int $new_fee
 * @property int $adjusted_obligation_id
 * @property float $payment_agreed
 * @property string $currency
 * @property string $origin
 * @property string $retrenched_policy
 *
 * @property \App\Model\Entity\AdjustedObligation $adjusted_obligation
 */
class AdjustedObligationsDetail extends Entity
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
