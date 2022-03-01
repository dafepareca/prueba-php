<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Audit Entity.
 *
 * @property string $id
 * @property string $event
 * @property string $model
 * @property string $entity_id
 * @property string $json_object
 * @property string $description
 * @property string $source_id
 * @property \Cake\I18n\Time $created
 * @property int $delta_count
 * @property string $source_ip
 * @property string $source_url
 * @property \App\Model\Entity\AuditDelta[] $audit_deltas
 * @property \App\Model\Entity\AccessLog $access_log
 */
class Audit extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'created' => true,
        'event' => true,
        'model' => true,
        'entity_id' => true,
        'json_object' => true,
        'description' => true,
        'source_id' => true,
        'delta_count' => true,
        'source_ip' => true,
        'source_url' => true,
        'entity' => true,
        'source' => true,
        'audit_deltas' => true,
    ];
}
