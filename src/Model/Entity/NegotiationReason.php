<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NegotiationReason Entity
 *
 * @property int $idnegotiation_reason
 * @property int $id_reason
 * @property string $Descption_reason
 * @property string $code_reason
 * @property string $codigo_terceros
 *
 * @property \App\Model\Entity\HistoryCustomer[] $history_customers
 */
class NegotiationReason extends Entity
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
        'codigo_terceros' => true,
        'idnegotiation_reason' => false
    ];
}
