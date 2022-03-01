<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ValueStage Entity
 *
 * @property int $id
 * @property int $legal_codes_id
 * @property int $city_offices_id
 * @property float $value
 *
 * @property \App\Model\Entity\LegalCode $legal_code
 * @property \App\Model\Entity\CityOffice $city_office
 */
class ValueStage extends Entity
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
