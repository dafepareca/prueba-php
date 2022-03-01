<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ticket Entity
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $user_id
 * @property int $ticket_type_error_id
 * @property int $solved_by
 * @property string $resolved_detail
 * @property int $approved_by
 * @property int $priority
 * @property array $priorities
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $date_delivery
 * @property int $ticket_state_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Solved $solved
 * @property \App\Model\Entity\Approved $approved
 * @property \App\Model\Entity\TicketsStatus $tickets_status
 * @property \App\Model\Entity\TicketsTypesError $tickets_types_error
 * @property \App\Model\Entity\TicketsResource[] $tickets_resources
 */
class Ticket extends Entity
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

    public $priorities = [
        1 => 'Alta',
        2 => 'Media',
        3 => 'Baja'
    ];
}
