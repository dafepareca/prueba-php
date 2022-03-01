<div class="row">
    <div class="col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4 sombra">
        <div class="col-md-12 col-xs-12 login_box" align="center">
            <div class="line"><h3><?= date('h : i A') ?></h3></div>
            <div class="outter">
                <?= $this->Html->image('analytics_login.jpg', ['class' => 'image-circle']) ?>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 login_control">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Flash->render() ?>
            <?= $this->Form->create() ?>
            <div class="control">
                <div class="label"><?= __('Email') ?></div>
                <?= $this->Form->input('email', array('placeholder' => __('Email'), 'required', 'label' => false)) ?>
            </div>
            <div align="center">
                <?= $this->Form->button(__('Get Token'), ['class' => 'btn-primary', 'type' => 'submit', 'div' => false]) ?>
                <?= $this->Html->link(__('Login'), ['controller' => 'users', 'action' => 'login'], ['escape' => false, 'class' => 'btn btn-default']) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>