<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-gray">
            <div class="inner">
                <h3><?=$users?></h3>
                <p><?=__('Users')?></p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <?= $this->Html->link($this->Html->icon('arrow-circle-right') . ' ' . __('More info'),
                ['controller' => 'business', 'action' => 'reportusers'],
                ['escape' => false, 'class' => 'small-box-footer load-ajax', 'update' => '#page-content-wrapper']) ?>
        </div>
    </div>
    <!--<div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <!--<div class="small-box bg-red">
            <div class="inner">
                <h3><?php#$adjustedObligations?></h3>
                <p><?php#__('Negotiations')?></p>
            </div>
            <div class="icon">
                <i class="fa fa-folder-open-o"></i>
            </div>
            <?php /*$this->Html->link($this->Html->icon('arrow-circle-right') . ' ' . __('More info'),
                ['controller' => 'adjustedObligations', 'action' => 'index'],
                ['escape' => false, 'class' => 'small-box-footer load-ajax', 'update' => '#page-content-wrapper']) */?>
        </div>
    </div>-->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?=$logs?></h3>
                <p><?=__('Log')?></p>
            </div>
            <div class="icon">
                <i class="fa fa-folder-open-o"></i>
            </div>
            <?= $this->Html->link($this->Html->icon('arrow-circle-right') . ' ' . __('More info'),
                ['controller' => 'logs', 'action' => 'index'],
                ['escape' => false, 'class' => 'small-box-footer load-ajax', 'update' => '#page-content-wrapper']) ?>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-gray">
            <div class="inner">
                <h3><?=$logsFiles?></h3>
                <p><?=__('Files Logs')?></p>
            </div>
            <div class="icon">
                <i class="fa fa-file"></i>
            </div>
            <?= $this->Html->link($this->Html->icon('arrow-circle-right') . ' ' . __('More info'),
                ['controller' => 'logs_files', 'action' => 'index'],
                ['escape' => false, 'class' => 'small-box-footer load-ajax', 'update' => '#page-content-wrapper']) ?>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-gray">
            <div class="inner">
                <h3><?=$rejected?></h3>
                <p><?=__('Rechazados')?></p>
            </div>
            <div class="icon">
                <i class="fa fa-search-plus"></i>
            </div>
            <?= $this->Html->link($this->Html->icon('arrow-circle-right') . ' ' . __('More info'),
                ['controller' => 'rejected', 'action' => 'index'],
                ['escape' => false, 'class' => 'small-box-footer load-ajax', 'update' => '#page-content-wrapper']) ?>
        </div>
    </div>

    <!--<div class="col-lg-3 col-xs-6">

        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>9999</h3>
                <p><?=__('Tickets')?></p>
            </div>
            <div class="icon">
                <i class="fa fa-warning"></i>
            </div>
            <?= $this->Html->link($this->Html->icon('arrow-circle-right') . ' ' . __('More info'),
                ['controller' => 'logs', 'action' => 'index'],
                ['escape' => false, 'class' => 'small-box-footer load-ajax', 'update' => '#page-content-wrapper']) ?>
        </div>
    </div>-->
</div>
