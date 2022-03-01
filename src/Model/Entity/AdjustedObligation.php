<?php
namespace App\Model\Entity;

use Cake\I18n\Date;
use Cake\ORM\Entity;

/**
 * AdjustedObligation Entity
 *
 * @property int $id
 * @property int $type_identification
 * @property int $identification
 * @property string $obligation
 * @property string $coordinator_name
 * @property float $customer_revenue
 * @property float $customer_paid_capacity
 * @property float $initial_payment_punished
 * @property float $payment_agreed
 * @property float $previous_minimum_payment
 * @property int $initial_fee
 * @property int $new_fee
 * @property bool $approved_committee
 * @property int $coordinator_id
 * @property Date $documentation_date
 * @property int $credit_payment_day
 * @property int $documents_required
 * @property string $office_name
 * @property string $customer_email
 * @property string $customer_telephone
 * @property \Cake\I18n\FrozenTime $date_negotiation
 * @property string $user_dataweb
 * @property string $reason_rejection
 *
 * @property \App\Model\Entity\AdjustedObligationsDetail[] $adjusted_obligations_details
 */
class AdjustedObligation extends Entity
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
