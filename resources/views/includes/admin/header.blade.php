<!-- Navbar-->
<header class="app-header">
    <a class="app-header__logo" href="{{ URL('admin/home') }}"><img src="<?php echo URL :: to(config('app.logow')); ?>" alt="Logo"/></a>
    <!-- Sidebar toggle button-->
    <a class="app-sidebar__toggle"  href = " javascript:void(0) " data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">

        <!--Notification Menu-->

        <!-- User Menu-->
        <li class="dropdown">
            <a class="app-nav__item"  href = " javascript:void(0) " data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</header>
<div class="loader_div" style="display:none">
  <div class="spinner">
    <div class="rect1"></div>
    <div class="rect2"></div>
    <div class="rect3"></div>
    <div class="rect4"></div>
    <div class="rect5"></div>
  </div>
</div>
