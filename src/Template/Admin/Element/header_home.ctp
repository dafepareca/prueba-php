<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container topnav">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header navbar-header-2">
            <a class="" href="#">
                <?=$this->Html->image('logo-davivienda.png', ['style'=> 'padding-bottom: 21px; padding-left: 15px; padding-top: 21px','class' => 'img-responsive'])?>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <div class="text-center">
                        <h5 style="margin: 28px;" class="dataweb"><?=__('FECHA ACTUALIZACIÓN:')?> <?=$lastUploadDate;?> Hrs  |  <a href="<?= $this->Url->build('/users/logout', true);?>"><?=__('SALIR')?></a> </h5>
                    </div>
                </li>
                <li>
                    <div class="pull-right">
                        <!-- <h1  style="margin: 14px;" class="dataweb">Davinegociador</h1> -->
                        <?=$this->Html->image('logo-davinegociador-white.png', ['style'=> 'padding: 14px 10px; max-width: 250px;','class' => 'img-responsive'])?>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
    <?php if(isset($current_user)):?>
        <!--<div  class="col-lg-12" style="background-color: #c21b23; height: 30px; color: #fff; padding: 3px">
                <div style="margin-left: 40%">
                    <a class="pull-right" style="margin-left: 20%; margin-right: 30%; color: #fff" href="<?= $this->Url->build('/users/logout', true);?>"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Cerrar Sesión</a>
                    <p class="pull-right">Bienvenido(a), <?=$current_user['name']?></p>
                </div>
        </div>-->
    <?php endif;?>

</nav>
<nav class="content-informacion navbar navbar-default navbar-fixed-top navbar-menu">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div style="color: #ed1c27; display: inline">
                <i aria-hidden="true" class="fa fa-fw fa-user fa-3x"></i>
            </div>
            <div style="display: inline-block; font-size: 16px">
                <!--<p><strong>BIENVENIDO,DA</strong> </p>-->
                <p><?=$current_user['name']?></p>
            </div>
        </div>

        <?php

        /** @var  $permissions \App\Model\Entity\AdminPermission*/
        if(isset($permissions)):?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right link-menu">

                <?php if($permissions->data): ?>
                <li>
                    <?= $this->Html->link($this->Html->icon('database') . ' ' . __('Data'),
                        ['controller' => 'charges', 'action' => 'index'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>
                <?php endif; ?>

                <?php if($permissions->create_profile): ?>
                <li>
                    <?= $this->Html->link($this->Html->icon('users') . ' ' . __('Perfiles'),
                        ['controller' => 'business', 'action' => 'index'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>
                <?php endif; ?>

                <?php if($permissions->report): ?>
                <li>
                    <?= $this->Html->link($this->Html->icon('bar-chart') . ' ' . __('Reporte'),
                        ['controller' => 'pages', 'action' => 'reports'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>
                <?php endif; ?>
                <li>
                    <?= $this->Html->link($this->Html->icon('ticket') . ' ' . __('SOS'),
                        ['controller' => 'tickets', 'action' => 'index'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>

                <li>
                    <?= $this->Html->link($this->Html->icon('list') . ' ' . __('Trainings'),
                        ['controller' => 'trainings', 'action' => 'index'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</nav>

