<?php
/** @var  $dashboard \App\Model\Entity\DashboardsUrl */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('area-chart').' '.__('Dashboards');?></h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

            <div class="box-body">

                <div class="alert alert-<?=$typealert?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> <?=$title?></h4>
                    <?=$message?>
                </div>

            </div>

            </div>
            <!-- /.box -->
        </div>
    </div>
</section>