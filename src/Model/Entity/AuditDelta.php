<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AuditDelta Entity.
 *
 * @property string $id
 * @property string $audit_id
 * @property string $property_name
 * @property string $old_value
 * @property string $new_value
 *
 * @property \App\Model\Entity\Audit $audit
 */
class AuditDelta extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'audit_id' => true,
        'property_name' => true,
        'old_value' => true,
        'new_value' => true,
        'audit' => true,
    ];
}
