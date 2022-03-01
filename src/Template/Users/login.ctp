<div class="intro-header">
    <div class="container">
        <div style="margin-top: 30px" class="">
            <div class="col-lg-4 col-md-5 col-lg-offset-8 col-md-offset-4">
                <div style="padding-top: 140px" class="">
                    <div class="" style="">
                        <?= $this->Flash->render('auth') ?>
                        <?= $this->Flash->render() ?>
                        <?= $this->Form->create(null,['class'=>'login-form','autocomplete' => 'off']) ?>
                        <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">
                                    <?=$this->Html->icon('user');?>
                                </span>
                            <?= $this->Form->input('email', array('type'=>'text','placeholder' => __('Identification or Email'), 'required','autocomplete' => 'off', 'label' => false,'class' =>'input-sm')) ?>
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><?=$this->Html->icon('key');?></span>
                            <?= $this->Form->input('token', array('id'=>'password','placeholder' => __('Password'), 'required', 'type' => 'password', 'label' => false, 'autocomplete' => 'off','class' =>'input-sm')) ?>
                        </div>
                        <br>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-circle-arrow-right"></span> Entrar</button>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>