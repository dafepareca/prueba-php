<?php
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->element('google-analytics'); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= Configure::read('Site.name') ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="google-site-verification" content="VB964w5GXrMR7_T_afweAK7fmwvSbQBXcCEo72mu-s0" />
    <link rel="icon" href="<?php echo $this->request->webroot; ?>favicon.ico?v=4" />
    <script>
        var urlSite =  '<?=$this->request->webroot; ?>';
        var urlImageLoading = urlSite + 'img/loading.gif';
        var textLoading = '<?=__('Loading data .. please wait..')?>';
        var lang = {
            Error: "<?= __('Error') ?>",
            AccessDenied : "<?= __('Access denied / prohibited') ?>",
            SessionExpiredInactivity : "<?= __('Session may have expired due to inactivity') ?>",
            UnauthorizedZone : "<?= __('You are entering an unauthorized zone') ?>",
            CheckAdmin : "<?= __('If the problem persists, check with the platform administrator') ?>",
            Close : "<?= __('Close') ?>"
        };
    </script>
    <!-- Bootstrap 3.3.5 -->
    <?php echo $this->Html->css('bootstrap'); ?>
    <!-- Font Awesome -->
    <?php echo $this->Html->css('font-awesome.min'); ?>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->

    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <!-- Theme style -->
    <?php echo $this->Html->css('adminlte.min'); ?>
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <?php echo $this->Html->css('skins/skin-blue'); ?>

    <?php echo $this->Html->css('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min'); ?>

    <?php echo $this->Html->css('bootstrap_admin_davivienda'); ?>

    <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

    <?php echo $this->fetch('css'); ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 2.1.4 -->
    <?php echo $this->Html->script('/plugins/jQuery/jQuery-2.1.4.min'); ?>
    <!-- jQuery Form Plugin 3.28.0 -->
    <?php echo $this->Html->script('/plugins/jQuery/jquery.form'); ?>
    <!-- Bootstrap 3.3.5 -->
    <?php echo $this->Html->script('bootstrap'); ?>
    <!-- SlimScroll -->
    <?php echo $this->Html->script('/plugins/slimScroll/jquery.slimscroll.min'); ?>
    <!-- FastClick -->
    <?php echo $this->Html->script('/plugins/fastclick/fastclick'); ?>
    <!-- AdminLTE App -->
    <?php echo $this->Html->script('adminlte.min'); ?>
    <!-- Analytics App -->
    <?php echo $this->Html->script('davivienda'); ?>

    <?= $this->Html->script('bootbox.min') ?>
    <!-- Notify alerts-->
    <?php echo $this->Html->script('/plugins/notify/notify');?>
    <?php echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js');?>

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

    <?php echo $this->Html->script('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min'); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo $this->Url->build('/'.$current_user['role']['prefix']); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">
                <?=$this->Html->image('logo_mini.png')?>
            </span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><?=$this->Html->image('logo_black.png')?></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <?php echo $this->element('nav-top') ?>
    </header>
    <!-- Left side column. contains the sidebar -->
    <?php echo $this->element('aside-main-sidebar'); ?>
    <!-- =============================================== -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="page-content-wrapper">
        <?php echo $this->Flash->render(); ?>
        <?php echo $this->Flash->render('auth'); ?>

        <?php

        if(isset($update_password)) {
            echo $this->element('modal_update_password');
        }

        if(isset($update_rate_tdc)) {
            echo $this->element('modal_update_rate_tdc');
        }
        
        if(isset($update_rate_uvr)) {
            echo $this->element('modal_update_rate_uvr');
        }
        ?>

        <?php echo $this->fetch('content'); ?>
    </div>
    <!-- /.content-wrapper -->
    <?php echo $this->element('footer'); ?>
    <!-- Control Sidebar -->
    <?php //echo $this->element('aside-control-sidebar'); ?>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
        immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<?php
/* Modal para notificar error */
$content = __('Error');
echo $this->Modal->create(['id' => 'modalErrorAjax', 'class' => 'modal-danger']);
echo $this->Modal->header($this->Html->icon('ban-circle') . ' ' . __('Error'));
echo $this->Modal->body($content, ['class' => 'my-body-class']);
echo $this->Modal->footer([$this->Form->button(__('Close'), ['data-dismiss' => 'modal', 'class' => 'btn btn-default'])]);
echo $this->Modal->end();

/* Modal para confirmar eliminación de registro */
$content = '<h4>'.__('Are you sure you want to delete this element?').'</h4>';
echo $this->Modal->create(['id' => 'ConfirmDelete', 'class' => 'modal-danger confirm']);
echo $this->Modal->header($this->Html->icon('question-circle-o') . ' ' . __('Confirm Delete'), ['close' => true, 'class' => 'modal-header-default']);
echo $this->Modal->body($content, ['class' => 'my-body-class']);
echo $this->Modal->footer(
    [
        $this->Form->button(__('Cancel'), ['data-dismiss' => 'modal']),
        $this->Form->button(__('Delete'), ['data-dismiss' => 'modal', 'action' => '#', 'class' => 'btn btn-danger btn-delete'])
    ]
);
echo $this->Modal->end();


/* Modal para confirmar la aprobación tickets */
echo $this->element('modal_approved_ticket');

/* Modal para confirmar la archivación de infromacion de registro */
$content = '<h4>'.__('Are you sure you want to archive this element?').'</h4>';
echo $this->Modal->create(['id' => 'ConfirmArchive', 'class' => 'modal-warning confirm']);
echo $this->Modal->header($this->Html->icon('question-circle-o') . ' ' . __('Confirm Archive User'), ['close' => true, 'class' => 'modal-header-default']);
echo $this->Modal->body($content, ['class' => 'my-body-class']);
echo $this->Modal->footer(
    [
        $this->Form->button(__('Cancel'), ['data-dismiss' => 'modal']),
        $this->Form->button(__('Archive'), ['data-dismiss' => 'modal', 'action' => '#', 'class' => 'btn btn-danger btn-delete'])
    ]
);
echo $this->Modal->end();


/* Modal para ver informacion detalle */
echo $this->Modal->create(['id' => 'viewModal', 'class' => 'modal-info']);
echo $this->Modal->end();

/* Modal generico */
echo $this->Modal->create(['id' => 'genericModal', 'class' => 'modal-default']);
echo $this->Modal->end();


/* Modal para ver información de usuario logueado */
echo $this->Modal->create(['id' => 'infoUser']);
echo $this->Modal->header($this->Html->icon('ban') . ' ' . __('User'), ['class' => 'modal-header-warning']);
echo $this->Modal->body('<div>' . '' . '</div>');
echo $this->Modal->footer([$this->Form->button(__('Close'), ['data-dismiss' => 'modal', 'class' => 'btn btn-warning flat'])]);
echo $this->Modal->end();
?>
<!-- ./wrapper -->
<?php echo $this->fetch('script'); ?>
<?php echo $this->fetch('scriptBotton'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".navbar .menu").slimscroll({
            height: "200px",
            alwaysVisible: false,
            size: "3px"
        }).css("width", "100%");

        var a = $('a[href="<?php echo $this->request->webroot . $this->request->url ?>"]');
        if (!a.parent().hasClass('treeview')) {
            a.parent().addClass('active').parents('.treeview').addClass('active');
        }
    });
    // Funcionalidad para visualizar los dropdown en tablas
    $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "inherit" );
    });
    $('.table-responsive').on('hide.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "auto" );
    });

    $(document).on('click', '.load-ajax', function() {
        var loadurl = $(this).attr('href');
        var content =  $(this).attr('update');
        $.get(loadurl, function(data) {
            $(content).html(data);
            $(".dropdown").removeClass("open");
        });
        return false;
    });

    $(document).on('submit', '.form-ajax', function() {
        var content =  $(this).attr('update');
        $(this).ajaxSubmit({
            success: function(data) { $(content).html(data) }
        });
        return false;
    });

    $('#ConfirmDelete').on('hidden', function() {
        $(this).removeData('modal');
    });
    $('.confirm').on('show.bs.modal', function(e) {
        $action = $(e.relatedTarget).attr('data-action');
        $(".btn-delete").attr("href", $action);
    });
    $(".confirm .btn-delete").bind("click", function(e) {
        e.preventDefault();
        var action = $(this).attr('href');
        $('.confirm').modal('hide');
        $.ajax({
            dataType : "html",
            type : 'DELETE',
            success : function(data, textStatus) {
                $("#page-content-wrapper").html(data);
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
            },
            url : action
        });
        return false;
    });
</script>
</body>
</html>