<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container topnav">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="" href="#">
                <?=$this->Html->image('logo-davivienda.png', ['style'=> 'padding-bottom: 21px; padding-left: 15px; padding-top: 21px','class' => 'img-responsive'])?>
            </a>
            <!--<button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand topnav" href="<?= $this->Url->build('/', true);?>">

            </a>-->
        </div>


        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <?php if(isset($current_user)):?>
                <li>
                    <div class="text-center">
                        <h5 style="margin: 28px;" class="dataweb"><?=__('FECHA ACTUALIZACIÓN:')?> <?=$lastUploadDate;?> Hrs  |  <a href="<?= $this->Url->build('/users/logout', true);?>"><?=__('SALIR')?></a> </h5>
                    </div>
                </li>
                <?php endif;?>
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
