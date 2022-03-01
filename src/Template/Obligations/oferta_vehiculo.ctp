<?php
/**
 * Created by PhpStorm.
 * User: walter
 * Date: 7/09/18
 * Time: 03:04 PM
 */
?>
<?= $this->Form->create('', [
    'class' => 'form-acetar-oferta',
    'id' => 'form-acetar-oferta',
    'update' => '#resumen',
    'horizontal' => false,
]) ?>

    <div class="row div-separador">
        <fieldset class="container scheduler-border" style="padding: 0 1.4em 1.4em 50em !important">
            <legend class="scheduler-border">Alternativas de Solución</legend>
        </fieldset>
        <div class="col-md-7">
            <div style="border: 1px solid #000; padding: 8px; border-radius: 8px">
                <div class="table-responsive table-info">
                    <table class="table table-hover">
                        <thead class="thead-default">
                        <tr class="table-danger">
                            <!--<th style="background-color: #fff; border: none !important; color: #ed1c27"><?#=__('Acepta');?></th>-->
                            <th><?=__('Tipo')?></th>
                            <th><?=__('Obligación')?></th>
                            <th><?=__('Alternativa de Negociación')?></th>
                            <th><?=__('Plazo')?></th>
                            <th><?=__('Condonación')?></th>
                            <th><?=__('Nueva Cuota')?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var  $obligacion \App\Model\Entity\Obligation*/
                        foreach($obligaciones as $obligacion):
                            $tipo = $obligacion->type_obligation_id;
                            $attr='';
                            if(in_array($tipo,[\App\Model\Table\TypeObligationsTable::CXR,\App\Model\Table\TypeObligationsTable::TDC])){
                                $attr = 'checked disabled';
                            }
                            ?>
                            <tr>
                                <!--<td style="border: none;">-->
                                <?php #if($obligacion->estrategia != 1 && $obligacion->negociable == 0): ?>
                                <!--<div class="checkbox checkbox-table">
                                <label>
                                    <input name="obligacion[<?=$obligacion->id?>][acepta]" class="finalizar" type="checkbox"  value="1" <?#=$attr?>>
                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                </label>
                            </div>-->
                                <?php #endIf; ?>
                                <!--</td>-->
                                <td><?=$obligacion->type_obligation->type?></td>
                                <td><?=$obligacion->maskobligation?></td>
                                <td><?=$obligacion->estrategias[$obligacion->estrategia]?></td>
                                <td><?=$obligacion->nuevoPlazo?></td>
                                <td><?=$obligacion->condonacion?></td>
                                <td><?=$this->Number->Currency($obligacion->nuevaCuota, null, ['precision' => 0])?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="div-separador"></div>

        </div>
    </div>


<div class="text-center div-separador">
    <?php

        echo  $this->Form->button($this->Html->icon('check').__('Acepta'), ['update'=>'#resumen','id' => 'aceptar-oferta', 'type'=>'submit','class' => 'btn btn-primary aceptar_oferta', 'div' => false]);
        echo  $this->Form->button($this->Html->icon('times').__('Rechaza'), ['type'=>'button', 'id' => 'rechazar-oferta', 'class' => 'btn btn-primary', 'div' => false]);
    ?>

</div>

<div class="div-separador-2x"></div>

<?= $this->Form->end() ?>

<script>
    $('#plazo').mask('000.000.000.000.000', {reverse: true});
    $('#payment-agreed').mask('000.000.000.000.000', {reverse: true});
</script>

