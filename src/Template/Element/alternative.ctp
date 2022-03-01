<div class="col-sm-12">
    <fieldset class="scheduler-border">
        <legend class="scheduler-border"><?=__('Nueva Alternativa de Solución')?></legend>
    </fieldset>

    <div class="col-md-8">

        <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
            <div class="table-responsive table-info">
                <table class="table table-hover">
                    <thead class="thead-default">
                    <tr class="table-danger">
                        <!--<th style="background-color: #fff; border: none !important; color: #ed1c27"><?#=__('Acepta');?></th>-->
                        <th><?=__('Tipo')?></th>
                        <th><?=__('Obligación')?></th>
                        <th><?=__('Alternativa de Negociación')?></th>
                        <th><?=__('Pago sugerido')?></th>
                        <th><?=__('Pagos Acumulados')?></th>
                        <th><?=__('Pago real')?></th>
                        <th><?=__('Plazo')?></th>
                        <th><?=__('Nueva Cuota')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>9
                    <td></td>
                    <td></td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center div-separador">
        <?php echo  $this->Form->button($this->Html->icon('eye').__('Elegir Alternativa'), ['type'=>'button', 'id' => 'nueva-oferta', 'class' => 'btn btn-primary', 'div' => false]); ?>
    </div>
</div>




