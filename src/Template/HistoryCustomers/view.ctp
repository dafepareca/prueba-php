<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 14/08/17
 * Time: 05:05 PM
 */

/** @var  $historyCustomer \App\Model\Entity\HistoryCustomer*/
?>
<fieldset class="scheduler-border" style="padding: 0 1.4em 0em 0em !important">
    <legend class="scheduler-border"><?=__('Fecha de Gestión')?> : <?=$historyCustomer->created->format('Y/m/d')?></legend>
</fieldset>

<?php
    $col = '8';
    if(empty($historyCustomer->history_normalizations)){
        $col = '12';
    }
?>

<div class="col-sm-12">
    <?php if($historyCustomer->history_status_id == \App\Model\Table\HistoryStatusesTable::ACEPTADA || $historyCustomer->history_status_id == \App\Model\Table\HistoryStatusesTable::ACEPTADA_COMITE):?>
    <div class="checkbox" style="display: inline-block; margin-bottom: 30px">

        <?php //if($current_user['busines_id'] == 4): ?>
            <label style="font-size: 1em; display: inline-block">
                <input <?= ($historyCustomer->documents_delivered)?'checked disabled':'' ?>  id="<?=$historyCustomer->id; ?>" class="entraga_documentos" type="checkbox">
                <span  style="" class="cr"><i class="cr-icon fa fa-check"></i></span>
                <p  style="display: inline-block"><?=__('Cliente firmó documentos')?></p>
            </label>
        <?php //endif; ?>

        <label style="font-size: 1em; display: inline-block">
            <input <?= ($historyCustomer->customer_desist)?'checked disabled':'' ?> id="<?=$historyCustomer->id; ?>" class="cliente_desiste" type="checkbox">
            <span  style="" class="cr"><i class="cr-icon fa fa-check"></i></span>
            <p  style="display: inline-block"><?=__('Cliente desiste negociación')?></p>
        </label>
    </div>
    <?php endif; ?>

    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
        <h5 style="text-align: center; color: #ed1c27">Situación cliente</h5>
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
                foreach($historyCustomer->history_details as $detail): ?>
                    <tr>
                        <td><?=$detail->type_obligation->type?></td>
                        <td><?=$detail->maskobligation?></td>
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
    </div>
</div>

<div style="margin-top: 10px" class="col-sm-12"></div>

<?php if($historyCustomer->alternative == 'Si'): ?>

<div class="col-sm-<?=$col?>">
    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
        <h5 style="text-align: center; color: #ed1c27">Nueva situación cliente</h5>
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
                foreach($historyCustomer->history_details as $detail): ?>
                <tr>
                    <td>
                        <?php
                        if($detail->selected) {
                          echo $this->Html->icon('check', ['style' => 'color:#ed1c27']);
                        }
                        ?>
                        <?=$detail->type_obligation->type?>
                    </td>
                    <td><?=$detail->maskobligation?></td>
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

<?php if(!empty($historyCustomer->history_normalizations)): ?>
<div class="col-sm-4">
    <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
        <h5 style="text-align: center; color: #ed1c27">Unificación Productos Consumos</h5>
        <div class="table-responsive table-info">
            <table class="table table-hover">
                <thead class="thead-default">
                <tr class="table-danger">
                    <th><?=__('Cuota')?></th>
                    <th><?=__('Tasa')?></th>
                    <th><?=__('Plazo')?></th>
                </tr>
                </thead>
                <tbody>
            <?php
            /** @var  $normalizations \App\Model\Entity\HistoryNormalization*/
            foreach($historyCustomer->history_normalizations as $normalizations): ?>
                <tr>
                    <td>
                        <?php
                        if($normalizations->selected) {
                            echo $this->Html->icon('check', ['style' => 'color:#ed1c27']);
                        }
                        ?>
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


    <?php if(!empty($historyCustomer->history_punished_portfolios)): ?>
        <div class="col-sm-12 div-separador">
            <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                <h5 style="text-align: center; color: #ed1c27">Cartera Castigada</h5>
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
                        foreach($historyCustomer->history_punished_portfolios as $normalizations): ?>
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

<?php else: ?>
 <h4 class="text-center">Sin alternativa</h4>
<?php endIf; ?>
