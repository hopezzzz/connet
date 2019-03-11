<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <ul class="app-menu">
        <li>
            <a class="app-menu__item @if($current_url=='/admin/home') active @endif" href="{{ URL('/admin/home') }}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a>
        </li>
        <li>
            <a class="app-menu__item @if($current_url=='/admin/regions') active @endif" href="{{ URL('/admin/regions') }}"><i class="app-menu__icon fa fa-globe"></i><span class="app-menu__label">Regions</span></a>
        </li>
        <li>
            <a class="app-menu__item @if($current_url=='/admin/plans') active @endif" href="{{ URL('/admin/plans') }}"><i class="app-menu__icon fa fa-list"></i><span class="app-menu__label">Plans</span></a>
        </li>
        <li>
            <a class="app-menu__item @if($current_url=='/admin/customers' || $current_url=='/admin/off-customers') active @endif" href="{{ URL('/admin/customers') }}"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Customers</span></a>
        </li>
        <li>
            <a class="app-menu__item @if($current_url=='/admin/reporting') active @endif" href="{{ URL('/admin/reporting') }}"><i class="app-menu__icon fa fa-file-o"></i><span class="app-menu__label">Reporting</span></a>
        </li>
        <li>
            <a class="app-menu__item @if($current_url=='/admin/email-templates') active @endif" href="{{ URL('/admin/email-templates') }}"><i class="app-menu__icon fa fa-envelope"></i><span class="app-menu__label">Email Templates</span></a>
        </li>
        <li>
            <a class="app-menu__item @if($current_url=='/admin/call-setting') active @endif" href="{{ URL('/admin/call-setting') }}"><i class="app-menu__icon fa fa-phone"></i><span class="app-menu__label">Call Settings</span></a>
        </li>
        <!-- <li class="treeview"><a class="app-menu__item"  href = " javascript:void(0) " data-toggle="treeview"><i class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">Orders</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="treeview-item" href="order-list.html"><i class="icon fa fa-circle-o"></i> Order List</a></li>
            </ul>
         </li> -->
    </ul>
</aside>
