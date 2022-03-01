<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Charge Entity
 *
 * @property int $id
 * @property int $state
 * @property int $type_charge
 * @property bool $processed
 * @property string $file
 * @property string $name_file
 * @property string $file_dir
 * @property int $records_obligation
 * @property int $failed_obligation
 * @property int $records_customer
 * @property int $failed_customer
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Customer[] $customers
 * @property \App\Model\Entity\Obligation[] $obligations
 */
class Charge extends Entity
{

    var $estados = [
        1 => 'Eliminado',
        2 => 'Activo',
        3 => 'Desactivado',
        4 => 'En Cargue',
        9 => 'Pruebas'
    ];


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
