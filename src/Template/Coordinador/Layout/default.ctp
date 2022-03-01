<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Configure;

?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->element('google-analytics'); ?>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= Configure::read('Site.name') ?>
    </title>

    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css(['bootstrap','admin','bootstrap_admin_davivienda', 'plugins/datetimepicker/datetimepicker']) ?>
    <?php echo $this->Html->css('font-awesome.min'); ?>

    <?= $this->Html->script(['/plugins/jQuery/jQuery-2.1.4.min', 'bootstrap']) ?>
    <?= $this->Html->script('/plugins/jQuery/jquery.form'); ?>
    <?= $this->Html->script('/plugins/notify/notify') ?>
    <?= $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js') ?>
    <?=$this->Html->script(['/plugins/chartjs/Chart.min',]);?>
    <?=$this->Html->css(['/plugins/select2/select2.min',]);?>
    <?=$this->Html->script(['/plugins/select2/select2.full.min']);?>

    <?= $this->Html->script('/plugins/datetimepicker/moment-with-locales') ?>
    <?= $this->Html->script('/plugins/datetimepicker/datetimepicker') ?>

    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

    <script>
        var urlSite =  '<?=\Cake\Routing\Router::url('/coordinador/',true)?>';
        var urlWeebroot =  '<?=\Cake\Routing\Router::url('/',true)?>';
        var urlImageLoading = urlWeebroot + 'img/loading.gif';
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
</head>
<body role="login">
<!-- Container -->
<?= $this->element('button_customer');?>
<div class="container">
    <?= $this->element('header_home');?>
    <?= $this->Flash->render(); ?>
    <?= $this->Flash->render('auth'); ?>
    <div id="page-content-wrapper" class="page-content-wrapper">
        <?php echo $this->fetch('content'); ?>
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
    </div>

</div><!-- /.Container -->

<?= $this->Html->script('davivienda') ?>
<?= $this->Html->script('bootbox.min') ?>

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

/* Modal para ver informacion detalle */
echo $this->Modal->create(['id' => 'viewModal', 'class' => 'modal-danger']);
echo $this->Modal->end();

?>
<script type="text/javascript">

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
            url : action
        });
        return false;
    });
</script>
</body>
</html>