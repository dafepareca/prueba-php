<?php
/** @var  $office \App\Model\Entity\Office */
/** @var  $Html \Cake\View\Helper\HtmlHelper */
?>
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('list') . ' ' . __('Offices') ?> |
        <small><?= __('Add') ?></small>
    </h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('chevron-left') . ' ' . $this->Html->tag('span', __('Back'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'index','city_office_id' => $city->id], ['escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'update' => '#page-content-wrapper', 'title' => __('Back')]); ?>
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
                    <h3 class="box-title"><?= __('Office Information') ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create($office, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]);
                $this->Form->unlockField('schedules');
                ?>


                <div class="box-body">
                    <div class="cols col-md-6">
                        <?= $this->Form->input('name',['value' => 'Sucursal '.$city->name]) ?>
                        <?= $this->Form->input('address') ?>
                    </div>
                    <div class="col-md-6" id="divIps" style="">
                        <fieldset>
                            <legend><?php echo __('Schedules'); ?></legend>
                            <table id="ip-table" class="table table-hover table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th><?= '<label class="required"></label>' . __('Start Time') ?></th>
                                    <th><?= '<label class="required"></label>' . __('End Time') ?></th>
                                    <th><?= '<label></label>' . __('commentary') ?></th>
                                    <th width="20" align="center">
                                        <?= $this->Html->link($this->Html->icon('plus'),
                                            '#', ['escape' => false, 'class' => 'addIp btn btn-xs btn-info', 'title' => __('Add')]); ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($this->request->data['schedules'])) : ?>
                                    <?php for ($key = 0; $key < count($this->request->data['schedules']); $key++) : ?>
                                        <?php echo $this->element('schedules', array('key' => $key)); ?>
                                    <?php endfor; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </fieldset>
                        <script id="ip-template" type="text/x-underscore-template">
                            <?php echo $this->element('schedules'); ?>
                        </script>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-left">
                        <?= $this->Form->button($this->Html->icon('save').' '.__('Save'), ['class' => 'btn-success', 'escape' => false]) ?>
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
                $('.timepicker').datetimepicker({
                    locale: 'LT',
                    format: 'LT'
                });
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
</script>