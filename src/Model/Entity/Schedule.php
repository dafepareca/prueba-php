<?php
namespace App\Model\Entity;

use Cake\I18n\Time;
use Cake\ORM\Entity;
use Cake\Log\Log;

/**
 * Schedule Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $start_time
 * @property \Cake\I18n\FrozenTime $end_time
 * @property string $commentary
 * @property int $office_id
 *
 * @property \App\Model\Entity\Office $office
 */
class Schedule extends Entity
{

    public $start_time_format = '';
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

    public function _getStartTime($start_time)
    {

        if(!empty($start_time)) {
            if(is_string($start_time)) {
                $start_time = new Time($start_time);
            }
            return $start_time->format('H:i');
        }
        return $start_time;
    }

    public function _getEndTime($end_time){
        if(!empty($end_time)) {
            if(is_string($end_time)){
                $end_time = new Time($end_time);
            }
            return $end_time->format('H:i');
        }
        return $end_time;
    }


    public function formatStartTime(){
        if(is_string($this->start_time)) {
            $start_time = new Time($this->start_time);
            return $start_time->format('h:i a');
        }
        return $this->start_time->format('h:i a');
    }

    public function formatENdTime(){
        if(is_string($this->end_time)) {
            $end_time = new Time($this->end_time);
            return $end_time->format('h:i a');
        }
        return $this->end_time->format('h:i a');
    }
}
