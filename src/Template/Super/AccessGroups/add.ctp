<?php
/** @var  $accessGroup \App\Model\Entity\AccessGroup */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('ban') . ' ' . __('Access Groups') ?> |
        <small><?= __('Add') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index'], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]) ?>
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
                    <h3 class="box-title"><?= __('Access Group Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php $this->Form->setConfig('columns', [
                    'sm' => [
                        'label' => 3,
                        'input' => 9,
                        'error' => 0
                    ],
                    'md' => [
                        'label' => 4,
                        'input' => 8,
                        'error' => 0
                    ]
                ]);
                ?>
                <?= $this->Form->create($accessGroup, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true,
                ]);
                $this->Form->unlockField('ips_groups');
                ?>
                <div class="box-body">
                    <div class="cols col-md-6">
                        <div class="cols col-md-12">
                            <?= $this->Form->input('name') ?>
                        </div>
                        <div class="cols col-md-12">
                            <?= $this->Form->input('description', ['type' => 'textarea']) ?>
                        </div>
                        <div class="cols col-md-12">
                            <?= $this->Form->input('all_ips') ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="cols col-md-6" id="divIps"
                         style="<?= (empty($this->request->data) || $this->request->getData('all_ips') == 1) ? 'display:none' : ''; ?>">
                        <fieldset>
                            <legend><?= __('Ips') ?></legend>
                            <table id="ip-table" class="table table-hover table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th><?= '<label class="required"></label>' . __('Name') ?></th>
                                    <th><?= '<label class="required"></label>' . __('IP') ?></th>
                                    <th width="20" align="center">
                                        <?= $this->Html->link($this->Html->icon('plus'),
                                            '#', ['escape' => false, 'class' => 'addIp btn btn-xs btn-info', 'title' => __('Add')]) ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($this->request->data['ips_groups'])) : ?>
                                    <?php for ($key = 0; $key < count($this->request->data['ips_groups']); $key++) : ?>
                                        <?= $this->element('ips', array('key' => $key)) ?>
                                    <?php endfor; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </fieldset>
                        <script id="ip-template" type="text/x-underscore-template">
                            <?= $this->element('ips') ?>
                        </script>
                    </div>
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
    $(document).ready(function () {
        var
            ipTable = $('#ip-table'),
            ipBody = ipTable.find('tbody'),
            ipTemplate = _.template($('#ip-template').remove().text()),
            numberRows = ipTable.find('tbody > tr').length;
        ipTable
            .on('click', 'a.addIp', function (e) {
                e.preventDefault();
                $(ipTemplate({key: numberRows++}))
                    .hide()
                    .appendTo(ipBody)
                    .fadeIn('fast');
            })
            .on('click', 'a.removeIp', function (e) {
                e.preventDefault();
                $(this)
                    .closest('tr')
                    .fadeOut('fast', function () {
                        $(this).remove();
                    });
            });
        if (numberRows === 0) {
            ipTable.find('a.addIp').click();
        }
    });
    $('#all-ips').change(function () {
        if ($('#all-ips').prop('checked')) {
            $('#divIps').hide();
            $("#divIps :input").attr("required", false);
        } else {
            $("#divIps :input").attr("required", "required");
            $('#divIps').show();
        }
    });
    <?php
    if(empty($this->request->data) || $this->request->getData('all_ips') == 1){
        ?>$("#divIps :input").attr("required", false);<?php
    }
    ?>
</script>