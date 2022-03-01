<div class="user-panel">
    <div class="pull-left image">
        <?= $this->Nested->_thumb('photo', $current_user['attachment'], 'small', ['class' => 'img-circle', 'alt' => 'User Image']) ?>
    </div>
    <div class="pull-left info">
        <p><?=h($current_user['name'])?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> <?=h($current_user['role']['name'])?></a>
    </div>
</div>

