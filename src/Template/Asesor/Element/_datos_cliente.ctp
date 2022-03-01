<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 24/07/17
 * Time: 10:26 AM
 */
?>
<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title"><?=__('Datos cliente')?></h3>
    </div>
    <div class="panel-body">
        <p><strong><?=__('Nombre')?>:</strong> <?=$customer->name; ?> </p>
        <p><strong><?=__('Tipo identificación')?>:</strong> <?=$customer->customer_type_identification->type; ?></p>
        <p><strong><?=__('Identificación')?>:</strong> <?=$customer->id ?></p>
        <p><strong><?=__('Ingresos')?>:</strong> <?=$this->Number->Currency($customer->income,null,['precision' => 0]) ?></p>
        <p><strong><?=__('Capacidad de pago inicial')?>:</strong> <?=$this->Number->Currency($negociacion['capacidad_pago_inicial'],null,['precision' => 0]) ?></p>
    </div>
</div>
