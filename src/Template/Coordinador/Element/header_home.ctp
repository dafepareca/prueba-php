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
                <p>
                    <?=$current_user['name']?> 
                    <?php 
                    if (1296000 > (strtotime($current_user['end_date']->i18nFormat('yyyy-MM-dd')) - time())) { ?>
                        <span style="background-color: #ed1c27;color: white;padding: 2px 5px;border-radius: 5px;display: block;text-align: center;margin-bottom: 5px;font-weight: bold;">
                        Su cuenta expirará el <?= $current_user['end_date']->i18nFormat('dd MMM YYYY') ?>. Contacte al administrador.
                        </span>
                    <?php } ?>
                </p> 
            </div>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right link-menu">


                <li>
                    <?= $this->Html->link($this->Html->icon('database') . ' ' . __('Comite'),
                        ['controller' => 'committees', 'action' => 'index'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>

                <li>
                    <?= $this->Html->link($this->Html->icon('users') . ' ' . __('Gestionar clientes'),
                        ['controller' => 'customers', 'action' => 'consult'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>

                <li>
                    <?= $this->Html->link($this->Html->icon('ticket') . ' ' . __('SOS'),
                        ['controller' => 'tickets', 'action' => 'index'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>

                <li>
                    <?= $this->Html->link($this->Html->icon('question') . ' ' . __('Preguntas Frecuentes'),
                        ['controller' => 'trainings', 'action' => 'index'],
                        ['escape' => false, 'class' => 'load-ajax', 'update' => '#page-content-wrapper']) ?>
                </li>

            </ul>
        </div>

    </div>
</nav>

