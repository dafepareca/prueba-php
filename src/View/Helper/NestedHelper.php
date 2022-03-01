<?php
namespace App\View\Helper;

use Cake\Log\Log;
use Cake\View\Helper;

class NestedHelper extends Helper
{
    public $helpers = ['Html'];

    public function label(array $list, $field, $type = 'default', $msg_nofound = '' ){
        $html = '';
        if(count($list) > 0){
            foreach($list as $item)
                $html .= '<span class="label label-'.$type.'">'.$item->$field.'</span> ';
        }else{
            $html = $msg_nofound;
        }
        return $html;
    }

    public function _thumb($field, $data, $size = null, $options = []){

        if($size !== null){
            $size = $size.'_';
        }
        $filename = $data['file_dir'].$size.$data[$field];
        if(file_exists('img'.'/'.$filename)){
            return $this->Html->image($filename.'?'.time(), array_merge(['alt' => 'Analytics'], $options));
        }else{
            return $this->Html->image('/img/default_avatar_'.$size.'.jpg', array_merge(['alt' => __('Image no found')], $options));
        }
    }

}