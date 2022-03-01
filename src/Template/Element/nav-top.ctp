<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?= $this->Nested->_thumb('photo', $current_user['attachment'], 'small', ['class' => 'user-image', 'alt' => 'User Image']) ?>
                    <span class="hidden-xs"><?=h($current_user['name'])?></span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                        <?= $this->Nested->_thumb('photo', $current_user['attachment'], 'large', ['class' => 'user-circle', 'alt' => 'User Image']) ?>
                        <p>
                            <?= h($current_user['name']) ?>
                            <small><?= h($current_user['role']['name']) ?></small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <?= $this->Html->link(__('Profile'), ['controller' => 'users', 'action' => 'profile', 'prefix' => false], [
                                'class' => 'btn btn-sm btn-default btn-flat load-ajax', 'update' => '#page-content-wrapper']) ?>
                        </div>
                        <div class="pull-right">
                            <?= $this->Html->link(__('Sign out'), ['controller' => 'users', 'action' => 'logout', 'prefix' => false], [
                                'class' => 'btn btn-sm btn-default btn-flat']) ?>
                        </div>
                    </li>
                </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            <!--
            <li>
                <a href="#" data-toggle="control-sidebar"><?=$this->Html->icon('cog')?></a>
            </li>
            -->
        </ul>
    </div>
</nav>