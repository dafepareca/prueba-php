<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 9/08/18
 * Time: 02:14 PM
 */
?>


        <div class="panel panel-default">
            <div class="panel-heading">Resultado valoración</div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt><?= __('Condonación Total') ?></dt>
                    <dd><?= $resultado['condonacion_total']*100 ?>%</dd>

                    <dt><?= __('Pago Total') ?></dt>
                    <dd><?= $this->Number->Currency($resultado['pago_total'], null, ['precision' => 0]) ?></dd>


                </dl>
            </div>
        </div>


        <!--<div class="panel panel-default">
            <div class="panel-heading">Modelo Experto</div>
            <div class="panel-body">
                <dl class="dl-horizontal">
                    <dt><?= __('Tir Inicial') ?></dt>
                    <dd><?= $obligacion->rate ?> %</dd>
                    <dt><?= __('Condonación Capital') ?></dt>
                    <dd><?= $resultado['condonacion_capital']*100 ?>%</dd>
                    <dt><?= __('Pago Total') ?></dt>
                    <dd><?= $this->Number->Currency($resultado['pago_total_experto'], null, ['precision' => 0]) ?></dd>
                    <dt><?= __('Tir final') ?></dt>
                    <dd><?= $resultado['tir_final']*100 ?> %</dd>
                    <dt><?= __('Delta Tir') ?></dt>
                    <dd><?= $resultado['delta_tir']*100 ?>%</dd>
                </dl>
            </div>
        </div>-->

