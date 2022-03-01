<?php echo ('<h1>Resultado...</h1>');
// $nombres es la variable a la que le asignábamos el resultado de nuestra consulta en
// la función listar en nuestro controlador
if (empty($rejected)) {
    //Si no tienen nada nos dirá que no existen nombre actualmente
    echo ("Actualmente no hay Datos.");
} else {
    ?>
    <section class="content-header">
        <h3 class="pull-left"><?= $this->Html->icon('search-plus') . ' ' . __('Lista').' ' ?>|
            <small><?= __('Rechazos') ?></small></h3>
        <div class="btn-group pull-right" role="group">
            <?= $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
                ['action' => 'index'],
                ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Refresh')]);
            ?>
        </div>
        <div class="clearfix"></div>
    </section>
    <?= $this->element('paging_options'); ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">

                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-condensed">
                        <thead>
                        <tr>
                           <th>Tipo Rechazos</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rejected as $data):?>
                           <tr>
                                <th><?= $data->type_rejected->description; ?></th>
                                <th><?= $data->count; ?></th>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<?php
}