<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TrainingResource Entity
 *
 * @property int $id
 * @property string $resource
 * @property string $file_dir
 * @property string $file_size
 * @property string $file_type
 * @property int $training_id
 *
 * @property \App\Model\Entity\Training $training
 */
class TrainingResource extends Entity
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
