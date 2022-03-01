<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 24/08/17
 * Time: 02:24 PM
 */
?>
<div class="text-center customerinfo">
    <?= $this->Form->button($this->Html->icon('plus'),
        [
            'id' => 'customerinfo',
            'data-toggle'=>'collapse',
            'data-target'=>'.collapseInfo',
            'class' => 'botonF1 btn btn-primary',
            'div' => false,
            'style' => 'display:none'
        ]) ?>
</div>
