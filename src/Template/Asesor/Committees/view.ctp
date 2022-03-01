<?php
/** @var  $committee \App\Model\Entity\Committee */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Committee Information') ?></h4>
</div>
<?= $this->Form->create($committee, [
    'id' => 'form-aceptar-comite',
    'update' => '#page-content-wrapper',
    'horizontal' => true,
    'data-id' => $committee->id
]) ?>
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

            <?php if(!empty($committee->history_customer->history_details)):
                ?>
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
                                <!--<th><?=__('Fecha de corte')?></th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            /** @var  $obligation \App\Model\Entity\HistoryDetail*/
                            foreach($committee->history_customer->history_details as $obligation): ?>
                                <tr>
                                    <td><?=$obligation->type_obligation->type?></td>
                                    <td><?=$obligation->obligation?></td>
                                    <td><?=$this->Number->Currency($obligation->total_debt, null, ['precision' => 0]) ?></td>
                                    <td><?=$this->Number->Currency($obligation->fee, null, ['precision' => 0]) ?></td>
                                    <td><?=$this->Number->Currency($obligation->minimum_payment, null, ['precision' => 0]) ?></td>
                                    <td><?=$obligation->days_past_due ?></td>
                                    <td><?=$obligation->rate_em ?>%</td>
                                    <td><?=$obligation->rate_ea ?>%</td>
                                    <!--<td><?php #$obligation->date_cut->format('Y-m-d') ?></td>-->
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
            <?php endIf; ?>


            <fieldset class="scheduler-border" style="padding: 0 1.4em 0em 0em !important">
                <legend class="scheduler-border"><?=__('Detalle de Gestión')?></legend>
            </fieldset>

            <div class="div-separador"></div>
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
            if(empty($committee->history_customer->history_details)){
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
                                        <td><?=$normalizations->rate?>%</td>
                                        <td><?=$normalizations->term?></td>
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

</div>
<!-- /.modal-body -->
<?= $this->Form->end() ?>
