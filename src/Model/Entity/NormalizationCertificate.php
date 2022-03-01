<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NormalizationCertificate Entity
 *
 * @property int $id
 * @property int $normalization_id
 * @property int $certificate_id
 *
 * @property \App\Model\Entity\Normalization $normalization
 * @property \App\Model\Entity\Certificate $certificate
 */
class NormalizationCertificate extends Entity
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
