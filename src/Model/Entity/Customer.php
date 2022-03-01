<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Customer Entity
 *
 * @property int $id
 * @property int $cnd
 * @property string $name
 * @property string $contact_name
 * @property string $contact_email
 * @property string $contact_position
 * @property string $contact_phone
 * @property string $circular_026
 * @property int $business_unit_count
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\HistoryCustomers[] $history_customers
 * @property \App\Model\Entity\BusinessUnit[] $business_units
 * @property \App\Model\Entity\CustomerTypeIdentification $customer_type_identification
 */
class Customer extends Entity
{

    var $cndMensaje = '';
    var $cndNegociar = true;
    var $legalProcess = false;

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
        'id' => true
    ];
}
