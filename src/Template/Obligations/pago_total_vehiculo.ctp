<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 5/09/18
 * Time: 10:29 AM
 */

/** @var \App\View\AppView $this */
?>

<div class="row div-separador">
    <fieldset class="container scheduler-border">
        <legend class="scheduler-border">Calculadora De Vehiculo</legend>
    </fieldset>

    <?php
    /** @var  $obligacion \App\Model\Entity\Obligation*/
    foreach ($obligaciones as $obligacion):
        if($obligacion->type_obligation_id == \App\Model\Table\TypeObligationsTable::VEH && ($obligacion->estrategia == 15 || $obligacion->estrategia == 14)):
        ?>
    <div class="col-md-12" style="margin-top: 10px !important;">
        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <div class="table-responsive table-info">
                <table class="table table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <!--<th style="background-color: #fff; border: none !important; color: #ed1c27"><? #=__('Acepta');?></th>-->
                        <th><?= __('Tipo') ?></th>
                        <th><?= __('Obligación') ?></th>
                        <th><?= __('Alternativa de Negociación') ?></th>
                        <th><?= __('Saldo total') ?></th>
                        <th><?= __('Saldo capital') ?></th>
                        <th><?= __('Días mora') ?></th>
                        <th><?= __('Tipo cartera') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $obligacion->type_obligation->type ?></td>
                            <td><?= $obligacion->maskobligation ?></td>
                            <td>Pago total</td>
                            <td><?= $this->Number->Currency($obligacion->total_debt, null, ['precision' => 0]) ?></td>
                            <td><?= $this->Number->Currency($obligacion->saldo_contable, null, ['precision' => 0]) ?></td>
                            <td><?= $obligacion->days_past_due ?></td>
                            <td><?= ($obligacion->company == 9)?'Castigada':'Vigente' ?></td>
                        </tr>
                    </tbody>
                </table>

                    <div id="conten-calculadora">
                        <div class="col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Condiciones actuales de la garantía</div>
                                <div class="panel-body">
                                        <?= $this->Form->create('', [
                                            'class' => 'form-ajax',
                                            'update' => '#resultado-'.$obligacion->id,
                                            'horizontal' => true,
                                            'url' => ['controller' => 'obligations', 'action' => 'resultado','id' => $obligacion->id]
                                        ]) ?>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="email">Fecha avalúo:</label>
                                                <div class="col-sm-8">
                                                    <input name="vehiculo[<?= $obligacion->id; ?>][fecha_avaluo]" value="<?= $obligacion->fecha_avaluo; ?>"  type="text" class="evaluar form-control input-sm datetimepicker" id="email" placeholder="Fecha avalúo">
                                                </div>
                                            </div>
                                        </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="email">Tipo avalúo:</label>
                                            <div class="col-sm-8">
                                                <select name="vehiculo[<?= $obligacion->id; ?>][tipo_avaluo]" class="evaluar form-control input-sm">
                                                    <option value="1">Perito</option>
                                                    <option value="2">Fasecolda</option>
                                                    <option value="3">Revista motor</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="valor_avaluo">Valor Avalúo garantia:</label>
                                            <div class="col-sm-8">
                                                <input name="vehiculo[<?= $obligacion->id; ?>][valor_avaluo]" value="<?= $obligacion->valor_avaluo; ?>" type="text" class="evaluar pagos form-control input-sm" id="valor_avaluo" placeholder="Avalúo garantia">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="email">Deuda parqueadero:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="vehiculo[<?= $obligacion->id; ?>][deuda_parqueadero]" class="evaluar pagos form-control input-sm" id="deuda_parqueadero" placeholder="Deuda parqueadero">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="email">Deuda comparendos:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="vehiculo[<?= $obligacion->id; ?>][deuda_comparendos]" class="evaluar pagos form-control input-sm" id="pagos deuda_comparendos" placeholder="Deuda comparendos">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="email">Deuda impuestos:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="vehiculo[<?= $obligacion->id; ?>][deuda_impuestos]" class="evaluar pagos form-control input-sm" id="deuda_impuestos" placeholder="Deuda impuestos">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="email">Gastos mantenimiento:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="vehiculo[<?= $obligacion->id; ?>][gastos_mantenimiento]" class="evaluar pagos form-control input-sm" id="gastos_mantenimiento" placeholder="Gastos mantenimiento">
                                            </div>
                                        </div>
                                    </div>
                                        <!--<div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-sm btn-primary pull-right">Consultar</button>
                                            </div>
                                        </div>-->

                                    <?= $this->Form->end() ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4" id="resultado-<?=$obligacion->id?>">

                        </div>
                </div>

            </div>
        </div>
    </div>

        <?php
    endIf;
    endforeach; ?>

    <div class="div-separador-2x"></div>

    <div class="row">
        <div class="col-md-10 abonos">
            <?=$this->Form->input('cliente_oferta_vehiculo',
                [
                    'label' =>  __('Pago cliente').': ',
                    'class' => 'evaluar div-false pagos',
                    'required' => 'required',
                    'type' => 'text'
                ]
            );?>

        </div>
    </div>
</div>

<div style="margin-bottom: 30px;" class="text-center">
    <?= $this->Form->button(__('Enviar oferta'), ['id' => 'enviar_oferta', 'update'=>'#oferta', 'class' => 'btn btn-primary enviar_oferta', 'div' => false]) ?>
</div>

<div class="row div-separador oferta_vehiculo">

</div>

<script>
    $(function () {
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'es',
            daysOfWeekDisabled: [0]
        });
    });

    $('.pagos').mask('000.000.000.000.000', {reverse: true});
</script>
