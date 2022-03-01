<?php
use Cake\Core\Configure;
?>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b><?= __('Version') ?></b> <?= Configure::read('Site.version') ?>
    </div>
    <?= Configure::read('Site.copyright') ?>
</footer>