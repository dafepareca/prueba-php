<?php
/** @var  $adjustedObligation \App\Model\Entity\Adjusted Obligations */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
/** @var  $adjustedObligation \App\Model\Entity\AdjustedObligation*/
?>
<div class="modal-header modal-header-info">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title"><?= $this->Html->icon('info-circle').' '.__('Adjusted Obligation Information') ?></h4>
</div>
<!-- /modal-header -->
<div class="modal-body">
    <dl class="dl-horizontal">
        <dt><?= __('Date Negotiation') ?></dt>
        <dd><?= $adjustedObligation->date_negotiation->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Type Identification') ?></dt>
        <dd><?= h($adjustedObligation->type_identification) ?></dd>
        <dt><?= __('Identification') ?></dt>
        <dd><?= h($adjustedObligation->identification) ?></dd>
        <dt><?= __('Cliente') ?></dt>
        <dd><?= h($adjustedObligation->customer_name) ?></dd>
        <dt><?= __('Email cliente') ?></dt>
        <dd><?= h($adjustedObligation->customer_email) ?></dd>
        <dt><?= __('Ingresos') ?></dt>
        <dd><?= '$'.$this->Number->format($adjustedObligation->customer_revenue) ?></dd>
        <dt><?= __('Capacidad de Pago') ?></dt>
        <dd><?= '$'.$this->Number->format($adjustedObligation->customer_paid_capacity) ?></dd>
        <dt><?= __('Office Name') ?></dt>
        <dd><?= h($adjustedObligation->office_name) ?></dd>
        <dt><?= __('Customer Email') ?></dt>
        <dd><?= h($adjustedObligation->customer_email) ?></dd>
        <dt><?= __('User Dataweb') ?></dt>
        <dd><?= h($adjustedObligation->user_dataweb) ?></dd>
        <dt><?= __('Documentation Date') ?></dt>
        <dd><?= $adjustedObligation->documentation_date->format('Y-m-d h:i:s A') ?></dd>
        <dt><?= __('Payment Agreed') ?></dt>
        <dd><?= $this->Number->format($adjustedObligation->payment_agreed) ?></dd>


    </dl>
</div>

<?php if (!empty($adjustedObligation->adjusted_obligations_details)): ?>
    <br>
    <div class="box no-border">
        <div class="box-body no-padding" style="max-height:200px; overflow:auto;" >
            <table class="table table-hover table-condensed table-striped">
                <thead>
                <tr>
                    <th width="120"><?= __('Field') ?></th>
                    <th><?= __('Old Value') ?></th>
                    <th><?= __('New Value') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                /** @var  $detail \App\Model\Entity\AdjustedObligationsDetail*/
                foreach ($adjustedObligation->adjusted_obligations_details as $detail): ?>
                    <tr>
                        <td><?= h($detail->type_obligation) ?></td>
                        <td><?= h($auditDeltas->obligation) ?></td>
                        <td><?= h($auditDeltas->strategy) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- /.modal-body -->
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= __('Close') ?></button>
</div>
<!-- /modal-footer -->