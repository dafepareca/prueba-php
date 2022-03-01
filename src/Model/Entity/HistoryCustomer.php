<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HistoryCustomer Entity
 *
 * @property int $id
 * @property int $customer_id
 * @property string $income
 * @property string $payment_capacity
 * @property string $initial_payment_punished
 * @property string $reason_rejection
 * @property int $income_source
 * @property int $history_status_id
 * @property int $cnd
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $app_access_date
 * @property int $negotiation_reason_id
 * @property int $credit_payment_day
 * @property int $approval_tyc
 * @property \Cake\I18n\FrozenTime $scheduling_date
 * @property string $scheduling_reason
 * @property string $ip_address
 * @property string $session_id
 * @property int $canal_id
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\HistoryStatus $history_status
 * @property \App\Model\Entity\User $users
 * @property \App\Model\Entity\NegotiationReason $negotiation_reason
 * @property \App\Model\Entity\HistoryDetail[] $history_details
 * @property \App\Model\Entity\HistoryNormalization[] $history_normalizations
 * @property \App\Model\Entity\HistoryPunishedPortfolio[] $history_punished_portfolios
 * @property \App\Model\Entity\HistoryPaymentVehicle[] $history_payment_vehicles
 * @property string payment_agreed
 */
class HistoryCustomer extends Entity
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
