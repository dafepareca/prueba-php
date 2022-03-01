<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Dashboard Entity
 *
 * @property int $id
 * @property int $campaign_id
 * @property string $title
 * @property string $description
 * @property int $status
 * @property string $access_public
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Campaign $campaign
 * @property \App\Model\Entity\DashboardsUrl[] $dashboards_urls
 */
class Dashboard extends Entity
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
