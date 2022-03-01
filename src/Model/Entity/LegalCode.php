<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LegalCode Entity
 *
 * @property int $id
 * @property string $code
 * @property int $minimum_payment
 * @property string $description
 * @property bool $apply_mortgage_credit
 * @property bool $apply_consumer_credit
 * @property bool $in_process
 * @property bool $parking
 * @property string $probability
 * @property int $order_legal
 * @property string $term
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 */
class LegalCode extends Entity
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

    public function getLabelOpcion(){
        $opciones = array(
            1 => 'No aplica',
            2 => 'Promedio entre saldo total y contable',
            3 => 'El mayor valor entre la liquidación aprobada y el saldo contable'
        );
        return $opciones[$this->minimum_payment];
    }

}
