<?php
namespace App\Model\Entity;

use App\Auth\LegacyPasswordHasher;
use Cake\Chronos\Date;
use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * InternalCustomer Entity
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property int $role_id
 * @property int $type_identification_id
 * @property int $identification
 * @property int $limit_query
 * @property int $state_reason_id
 * @property bool $meets_requirement
 * @property string $name
 * @property string $position
 * @property string $mobile
 * @property string $token
 * @property \Cake\I18n\Date $start_date
 * @property \Cake\I18n\Date $end_date
 * @property int $user_status_id
 * @property int $access_group_id
 * @property \Cake\I18n\Time $last_login
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\AccessGroup $access_group
 * @property \App\Model\Entity\UserStatus $user_status
 * @property \App\Model\Entity\Attachment $attachment
 */
class Admin extends Entity
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

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token'
    ];

    protected function _setPassword($password){
        if (strlen($password) > 0) {
            return (new LegacyPasswordHasher)->hash($password);
        }
    }

    protected function _setToken($token){
        if (strlen($token) > 0) {
            return (new LegacyPasswordHasher)->hash($token);
        }
    }
}
