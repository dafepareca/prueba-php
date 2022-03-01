<?php
use Cake\Routing\Router;
/** @var  $settingCategories \App\Model\Entity\SettingCategory[] */
/** @var  $Html \Cake\View\Helper\HtmlHelper*/
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="pull-left"><?= $this->Html->icon('cog') . ' ' . __('Adjust Settings') ?></h1>
    <div class="btn-group pull-right">
        <?= $this->Html->link($this->Html->icon('refresh') . ' ' . $this->Html->tag('span', __('Refresh'), ['class' => 'hidden-xs hidden-sm']),
            ['action' => 'update'],
            ['update' => '#page-content-wrapper', 'escape' => false, 'class' => 'load-ajax btn btn-sm btn-default', 'title' => __('Refresh')]) ?>
    </div>
    <div class="clearfix"></div>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
                <!-- form start -->
                <?= $this->Form->create(null, [
                    'class' => 'form-ajax',
                    'update' => '#page-content-wrapper',
                    'horizontal' => true
                ]) ?>
                <div class="box-body">
                    <div class="box-group" id="accordion">
                        <?php foreach ($settingCategories as $settingCategory): ?>
                            <div class="panel box box-default box-solid">
                                <div class="box-header ">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#settingCategory_<?= $settingCategory->id ?>">
                                            <?= h($settingCategory->name) ?>:
                                        </a>
                                        <small><?= h($settingCategory->description) ?></small>
                                    </h4>
                                </div>
                                <div id="settingCategory_<?= $settingCategory->id ?>" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <?php foreach ($settingCategory->settings as $setting): ?>
                                        <div class="col-xs-6 col-lg-4">
                                            <?php
                                            if($setting->type_key == 'checkbox'){
                                                echo $this->Form->input(
                                                    'setting.'.$setting->id.'.name',
                                                    array(
                                                        'type' => 'checkbox',
                                                        'label' => __($setting->label),
                                                        'value' => $setting->value,
                                                        'checked' => ($setting->value>0)?'checked':'',
                                                        'help' => __($setting->description),
                                                        'required' => FALSE
                                                    )
                                                );
                                            }elseif($setting->type_key == 'textarea'){
                                                echo $this->Form->input(
                                                    'setting.'.$setting->id.'.name',
                                                    array(
                                                        'rows' => 3,
                                                        'type' => $setting->type_key,
                                                        'label' => __($setting->label),
                                                        'value' => $setting->value,
                                                        'help' => __($setting->description),
                                                        'required' => true
                                                    )
                                                );
                                            }elseif($setting->type_key == 'select'){
                                                $values = explode(',', $setting->options);
                                                $options = [];
                                                foreach($values as $value){
                                                    $options[$value] = $value;
                                                }
                                                echo $this->Form->input(
                                                    'setting.'.$setting->id.'.name',
                                                    array(
                                                        'type' => $setting->type_key,
                                                        'options' => $options,
                                                        'label' => __($setting->label),
                                                        'help' => __($setting->description),
                                                        'required' => true
                                                    )
                                                );
                                            }else{
                                                echo $this->Form->input(
                                                    'setting.'.$setting->id.'.name',
                                                    array(
                                                        'type' => $setting->type_key,
                                                        'label' => __($setting->label),
                                                        'value' => $setting->value,
                                                        'help' => __($setting->description),
                                                        'required' => true
                                                    )
                                                );
                                            }
                                            ?>
                                        </div>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
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
            <!-- /.box -->
        </div>
    </div>
</section>
<!-- /.content -->