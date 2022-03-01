<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AccessActivity Entity
 *
 * @property int $id
 * @property int $model_id
 * @property \Cake\I18n\Time $date
 * @property int $access_log_id
 * @property int $type_activity_id
 *
 * @property \App\Model\Entity\Model $model
 * @property \App\Model\Entity\AccessLog $access_log
 * @property \App\Model\Entity\AccessTypesActivity $access_types_activity
 */
class AccessActivity extends Entity
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
