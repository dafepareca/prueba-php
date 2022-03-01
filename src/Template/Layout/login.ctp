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
        <meta name="google-site-verification" content="VB964w5GXrMR7_T_afweAK7fmwvSbQBXcCEo72mu-s0" />
        <title>
            <?= Configure::read('Site.name') ?>
        </title>
        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->css(['bootstrap', 'login']) ?>
        <?php echo $this->Html->css('bootstrap_davivienda'); ?>
        <?php echo $this->Html->css('font-awesome.min'); ?>
        <?= $this->Html->script(['/plugins/jQuery/jQuery-2.1.4.min', 'bootstrap']) ?>
        <!-- Notify alerts-->
        <?= $this->Html->script('/plugins/notify/notify') ?>
        <?= $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js') ?>
        <?= $this->Html->script('login') ?>
        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
        <link href='//fonts.googleapis.com/css?family=Raleway:400,200' rel='stylesheet' type='text/css'>
    </head>
    <body>
    <?= $this->element('header_home');?>
        <!-- Container -->
        <div class="">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <?= $this->fetch('content'); ?>
        </div><!-- /.Container -->
    <?= $this->element('footer_home');?>
    </body>
</html>