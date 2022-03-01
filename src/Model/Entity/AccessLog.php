<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccessLog Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $access_activity_log_count
 * @property string $ip
 * @property string $user_agent
 * @property \Cake\I18n\Time $date_login
 * @property \Cake\I18n\Time $date_logout
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\LogoutsType $logouts_type
 * @property \App\Model\Entity\Audit[] $audits
 */
class AccessLog extends Entity
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
