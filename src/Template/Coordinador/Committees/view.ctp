<?php
/** @var  $committee \App\Model\Entity\Committee */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
/** @var  \App\View\AppView $this */
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Committee Information') ?></h4>
</div>
<?= $this->Form->create($committee, [
    'id' => 'form-aceptar-comite',
    'update' => '#page-content-wrapper',
    'horizontal' => true,
    'data-id' => $committee->id,
    'url' => ['controller' => 'committees','action' => 'aceptar_comite',$committee->id]
]) ?>
<?php
$this->Form->unlockField('normalizacion_id');
?>
<!-- /modal-header -->
<div class="modal-body">

    <div class="row informacion">
        <div class="">
            <div style="color: #ed1c27; display: inline">
                <?=$this->Html->icon('user fa-4x');?>
            </div>

            <div style="display: inline-block; font-size: 12px">
                <p><strong><?=__('Nombre Cliente')?>:</strong> <?=$committee->history_customer->customer_name;?> </p>
                <p><strong><?=__('Identificacion')?>:</strong> <?=$committee->history_customer->customer_id; ?></p>
            </div>

                    <div class="div-separador"></div>

                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border"><?=__('Detalle de Productos')?></legend>
                    </fieldset>

                    <div class="table-responsive table-info">
                        <table class="table table-hover">
                            <thead class="thead-default">
                            <tr class="table-danger">
                                <th><?=__('Tipo')?></th>
                                <th><?=__('Obligación')?></th>
                                <th><?=__('Saldo total')?></th>
                                <th><?=__('Cuota')?></th>
                                <th><?=__('Pago mínimo')?></th>
                                <th><?=__('Días mora')?></th>
                                <th><?=__('Tasa EA')?></th>
                                <th><?=__('Tasa EM')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /** @var  $detail \App\Model\Entity\HistoryDetail*/
                            foreach($committee->history_customer->history_details as $detail): ?>
                                <tr>
                                    <td><?=$detail->type_obligation->type?></td>
                                    <td><?=$detail->obligation?></td>
                                    <td><?=$this->Number->Currency($detail->total_debt, null, ['precision' => 0])?></td>
                                    <td><?=$this->Number->Currency($detail->fee, null, ['precision' => 0])?></td>
                                    <td><?=$this->Number->Currency($detail->minimum_payment, null, ['precision' => 0])?></td>
                                    <td><?=$detail->days_past_due; ?></td>
                                    <td><?=$detail->rate_ea?>%</td>
                                    <td><?=$detail->rate_em?>%</td>

                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>



            <fieldset class="scheduler-border" style="padding: 0 1.4em 0em 0em !important">
                <legend class="scheduler-border"><?=__('Detalle de Gestión')?></legend>
            </fieldset>
            <div class="div-separador"></div>
            <div class="text-center">
                <h5><span style="color: #ed1c27">Disponible para negociación:</span> <?=$this->Number->Currency($disponibleNegociacion, null, ['precision' => 0])?></h5>
            </div>
            <div class="table-responsive table-modal">
                <table class="table table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <th><?=__('Fecha')?></th>
                        <th><?=__('Hora')?></th>
                        <th><?=__('Ingresos')?></th>
                        <th><?=__('Capacidad de Pago')?></th>
                        <th><?=__('Alternativa')?></th>
                        <th><?=__('Pago Acordado')?></th>
                        <th><?=__('Usuario')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?=$committee->created->format('Y-m-d')?></td>
                        <td><?=$committee->created->format('H:i:s')?></td>
                        <td><?=$this->Number->Currency($committee->history_customer->income, null, ['precision' => 0])?></td>
                        <td><?=$this->Number->Currency($committee->history_customer->payment_capacity, null, ['precision' => 0])?></td>
                        <td><?=$committee->history_customer->alternative;?></td>
                        <td><?=$this->Number->Currency($committee->history_customer->payment_agreed, null, ['precision' => 0])?></td>
                        <td><?=$committee->history_customer->user->name;?></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <?php
            $col = '8';
            if(empty($committee->history_customer->history_normalizations)){
                $col = '12';
            }
            ?>
            <div class="col-sm-<?=$col?>">
                <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                    <div class="table-responsive table-info">
                        <table class="table table-hover">
                            <thead class="thead-default">
                            <tr class="table-danger">
                                <th><?=__('Tipo')?></th>
                                <th><?=__('Obligación')?></th>
                                <th><?=__('Alternativa de Negociación')?></th>
                                <th><?=__('Plazo')?></th>
                                <th><?=__('Nueva Cuota')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /** @var  $detail \App\Model\Entity\HistoryDetail*/
                            foreach($committee->history_customer->history_details as $detail): ?>
                                <tr>
                                    <td>
                                        <?php
                                        if($detail->selected) {
                                            echo $this->Html->icon('check', ['style' => 'color:#ed1c27']);
                                        }
                                        ?>
                                        <?=$detail->type_obligation->type?>
                                    </td>
                                    <td><?=$detail->obligation?></td>
                                    <td><?=$detail->strategy; ?></td>
                                    <td><?=$detail->term?></td>
                                    <td><?=$this->Number->Currency($detail->new_fee, null, ['precision' => 0])?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if(!empty($committee->history_customer->history_normalizations)): ?>
                <div class="col-sm-4">
                    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                        <h5 style="text-align: center; color: #ed1c27"><?=__('Unificación Productos Consumos')?></h5>
                        <div class="table-responsive table-info">
                            <table class="table table-hover">
                                <thead class="thead-default">
                                <tr class="table-danger">
                                    <th></th>
                                    <th><?=__('Cuota')?></th>
                                    <th><?=__('Tasa')?></th>
                                    <th><?=__('Plazo')?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                /** @var  $normalizations \App\Model\Entity\HistoryNormalization*/
                                foreach($committee->history_customer->history_normalizations as $normalizations):
                                    $checked = '';
                                    $disabled = '';
                                    if($normalizations->selected) {
                                        $selected = $normalizations->id;
                                        $checked = 'checked';
                                        $disabled = 'disabled';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="checkbox checkbox-table">
                                                <label>
                                                    <input class="aceptar_oferta_comite" type="checkbox" value="<?=$normalizations->id?>" <?= $checked ?> <?= $disabled ?>>
                                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <?=$this->Number->Currency($normalizations->fee, null, ['precision' => 0])?></td>
                                        <td><?=round($normalizations->rate,1)?>%</td>
                                        <td><?=$normalizations->term?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endIf; ?>

            <?php if(!empty($committee->history_customer->history_payment_vehicles)):
                /** @var  $ofertaVehiculo \App\Model\Entity\HistoryPaymentVehicle*/
                $ofertaVehiculo =  $committee->history_customer->history_payment_vehicles[0];
                ?>
                <div class="col-sm-12 div-separador">
                    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                        <h5 style="text-align: center; color: #ed1c27">Oferta Vehiculo</h5>
                        <div class="table-responsive table-info">
                            <table class="table table-hover">
                                <thead class="thead-default">
                                <tr class="table-danger">
                                    <th><?=__('Valor a pagar')?></th>
                                    <th><?=__('Plazo')?></th>
                                    <th><?=__('% Condonación.')?></th>
                                    <th><?=__('Valor condonación.')?></th>
                                    <th><?=__('Pago total Experto.')?></th>
                                    <th><?=__('% Condonación Experto.')?></th>
                                    <th><?=__('Valor condonación Experto.')?></th>
                                </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td><?=$this->Number->Currency($ofertaVehiculo->total_payment, null, ['precision' => 0])?></td>
                                        <td>1</td>
                                        <td><?=round($ofertaVehiculo->condonation*100,1)?>%</td>
                                        <td><?=$this->Number->Currency($ofertaVehiculo->value_condonation, null, ['precision' => 0])?></td>
                                        <td><?=$this->Number->Currency($ofertaVehiculo->total_payment_expert, null, ['precision' => 0])?></td>
                                        <td><?=round($ofertaVehiculo->condonation_expert*100,1)?>%</td>
                                        <td><?=$this->Number->Currency($ofertaVehiculo->value_condonation_expert, null, ['precision' => 0])?></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($committee->history_customer->history_punished_portfolios)): ?>
                <div class="col-sm-12 div-separador">
                    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                        <h5 style="text-align: center; color: #ed1c27">Unificación Cartera Castigada</h5>
                        <div class="table-responsive table-info">
                            <table class="table table-hover">
                                <thead class="thead-default">
                                <tr class="table-danger">
                                    <th><?=__('Cuota')?></th>
                                    <th><?=__('Plazo')?></th>
                                    <th><?=__('% Cond 1.')?></th>
                                    <th><?=__('Valor condonación 1.')?></th>
                                    <th><?=__('% Cond 2.')?></th>
                                    <th><?=__('Valor condonación 2.')?></th>
                                    <th><?=__('Pago inicial')?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                /** @var  $normalizations \App\Model\Entity\HistoryPunishedPortfolio*/
                                foreach($committee->history_customer->history_punished_portfolios as $normalizations): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            if($normalizations->selected) {
                                                echo $this->Html->icon('check', ['style' => 'color:#ed1c27']);
                                            }
                                            ?>
                                            <?=$this->Number->Currency($normalizations->fee, null, ['precision' => 0])?></td>
                                        <td><?=$normalizations->term?></td>
                                        <td><?=round($normalizations->initial_condonation*100,1)?>%</td>
                                        <td><?=$this->Number->Currency($normalizations->value_initial_condonation, null, ['precision' => 0])?></td>
                                        <td><?=round($normalizations->end_condonation*100,1)?>%</td>
                                        <td><?=$this->Number->Currency($normalizations->value_end_condonation, null, ['precision' => 0])?></td>
                                        <td><?=$this->Number->Currency($normalizations->initial_payment, null, ['precision' => 0])?></td>

                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endIf; ?>
        </div>
    </div>

    <div class="text-center div-separador">
        <?php
        if(isset($selected)) {
            echo $this->Form->control(
                'normalizacion_id',
                [
                    'id' => 'propuesta_aceptada',
                    'value' => $selected,
                    'class' => 'aceptar-comite',
                    'type' => 'hidden'
                ]
            );
        }
        ?>
        <button type="submit" class="btn btn-info"><?= __('Aceptar') ?></button>
        <button data-id="<?=$committee->id; ?>" id="rechazar-comite" type="button" class="btn btn-info"><?= __('Rechazar') ?></button>
    </div>

</div>
<!-- /.modal-body -->
<?= $this->Form->end() ?>
