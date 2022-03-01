<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Campaign Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property int $dashboard_count
 * @property int $business_unit_id
 * @property int $customer_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\BusinessUnit $business_unit
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Dashboard[] $dashboards
 * @property \App\Model\Entity\Document[] $documents
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\CampaignsUser[] $campaigns_users
 */
class Campaign extends Entity
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
