<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CityOffice Entity
 *
 * @property int $id
 * @property string $name
 * @property string $parking_no_capture
 * @property string $parking_agreement
 * @property string $parking_no_agreement
 *
 * @property \App\Model\Entity\Office[] $offices
 */
class CityOffice extends Entity
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
