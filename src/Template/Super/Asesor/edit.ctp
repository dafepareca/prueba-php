<?php
/** @var  $user \App\Model\Entity\User */
/** @var  $accessGroups \App\Model\Entity\AccessGroup[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper */


$controller = $this->request->params['controller'];
if(isset($business)){
    $businessId = $business->id;
}elseif($current_user['role_id'] == \App\Model\Table\RolesTable::Super || $current_user['role_id'] == \App\Model\Table\RolesTable::Admin){
    $controller = 'users';
    $businessId = null;
}

?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('users') . ' ' . __('Asesor') ?> |
        <small><?= __('Edit') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['controller' => $controller,'action' => 'index','busines_id' => $businessId], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
    </div>
    <div class="clearfix"></div>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= __('Asesor Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($user, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true,
                    'type' => 'file'
                ]) ?>
                <div class="box-body">
                    <div class="col-md-6">

                        <?= $this->Form->input('type_identification_id',['options' => $typeIdentifications, 'empty' => __('Select Item')]) ?>
                        <?= $this->Form->input('identification') ?>
                        <?= $this->Form->input('name') ?>
                        <?= $this->Form->input('mobile', ['prepend' => $this->Html->icon('phone')]) ?>
                        <?= $this->Form->input('meets_requirement') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->input('start_date', ['type' => 'text','class'=>'datetimepicker']) ?>
                        <?= $this->Form->input('end_date', ['type' => 'text','class'=>'datetimepicker']) ?>
                        <?= $this->Form->input('access_group_id', ['prepend' => $this->Html->icon('ban'), 'options' => $accessGroups, 'empty' => __('Select Item')]) ?>
                        <?= $this->Form->input('busines_id', ['required' => true, 'prepend' => $this->Html->icon('ban'), 'options' => $businessGroups, 'empty' => __('Select Item')]) ?>
                        <?= $this->Form->input('code_manager') ?>
                        <?= $this->Form->input('view_committees', ['prepend' => $this->Html->icon('ban'), 'options' => [0=>'No',1=>'Si']]) ?>
                        <?= $this->Form->input('role_id', ['options' => [2=>'Asesor',4=>'Coordinador']]) ?>
                        <?= $this->Form->input('user_status_id', ['options' => $userStatuses]) ?>
                        <?php
                        $display = 'block';
                        if($user->user_status_id == \App\Model\Table\UserStatusesTable::Active){
                            $display = 'none';
                        }
                        ?>
                        <div class="state_reason_id"  style="display: <?=$display?>">
                            <?= $this->Form->input('state_reason_id', ['options' => $stateReasons, 'empty' => __('Select Item')]) ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-left">
                        <?= $this->Form->button($this->Html->icon('save') . ' ' . __('Save'), ['class' => 'btn-success', 'escape' => false]) ?>
                    </div>
                    <div class="pull-right">
                        <i>* <?= __('Required fields') ?></i>
                    </div>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</section>
<script>
    $(function () {
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'es'
        });
    });

    $("#attachment-photo").change(function(){
        readURL(this, 'avatar');
    });

    $('input#name').bind('keyup', function(event) {
        var $this = $(this);
        var str = $this.val().toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
        $this.val(function() {
            return str;
        });
    });
    
</script>