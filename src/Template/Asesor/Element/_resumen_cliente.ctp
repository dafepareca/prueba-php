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
        <h3 class="panel-title"><?=__('Resumen actual cliente')?></h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-responsive table-bordered table-hover">
                <thead class="thead-default">
                    <th></th>
                    <th><?=__('Saldo total')?></th>
                    <th><?=__('Pagos mínimos')?></th>
                    <th><?=__('Cuotas pactadas')?></th>
                    <th><?=__('Nuevas cuotas')?></th>
                </thead>
                <tbody>
                    <tr>
                        <th><?=__('Hipotecario')?></th>
                        <td><?=$this->Number->Currency($resumenFinal['hipotecario']['total'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['hipotecario']['pagos_minimos'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['hipotecario']['cuotas'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['hipotecario']['nuevas_cuotas'], null, ['precision' => 0])?></td>
                    </tr>
                    <tr>
                        <th><?=__('Vehículo')?></th>
                        <td><?=$this->Number->Currency($resumenFinal['vehiculos']['total'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['vehiculos']['pagos_minimos'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['vehiculos']['cuotas'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['vehiculos']['nuevas_cuotas'], null, ['precision' => 0])?></td>
                    </tr>
                    <tr>
                        <th><?=__('Fijos')?></th>
                        <td><?=$this->Number->Currency($resumenFinal['fijos']['total'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['fijos']['pagos_minimos'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['fijos']['cuotas'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['fijos']['nuevas_cuotas'], null, ['precision' => 0])?></td>
                    </tr>
                    <tr>
                        <th><?=__('Rotativos')?></th>
                        <td><?=$this->Number->Currency($resumenFinal['rotativos']['total'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['rotativos']['pagos_minimos'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['rotativos']['cuotas'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['rotativos']['nuevas_cuotas'], null, ['precision' => 0])?></td>
                    </tr>
                    <tr>
                        <th><?=__('Total')?></th>
                        <td><?=$this->Number->Currency($resumenFinal['total']['total'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['total']['pagos_minimos'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['total']['cuotas'], null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($resumenFinal['total']['nuevas_cuotas'], null, ['precision' => 0])?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
