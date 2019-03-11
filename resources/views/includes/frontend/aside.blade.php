    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image">
        <div>
          <p class="app-sidebar__user-name">@if (Auth::check()){!! Auth::user()->name!!}@endif</p>
        
        </div>
      </div>
      <ul class="app-menu">
        <li><a class="app-menu__item"  href = " javascript:void(0) "><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
       
        <li class="treeview"><a class="app-menu__item"  href = " javascript:void(0) " data-toggle="treeview"><i class="app-menu__icon fa fa-upload"></i><span class="app-menu__label">Upload Sheet</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                <ul class="treeview-menu">
                        <li><a class="app-menu__item" href="{!! $url.'/customers' !!}"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">Upload Customer</span></a></li>

                  <li><a class="app-menu__item" href="{!! $url.'/suppliers' !!}"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">Uplaod Supplier</span></a></li>

                  <li><a class="app-menu__item" href="{!! $url.'/products' !!}"><i class="app-menu__icon  fa fa-product-hunt"></i><span class="app-menu__label">Upload Product</span></a></li>

                  <li><a class="app-menu__item" href="{!! $url.'/labels' !!}"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">Upload Label</span></a></li>

                </ul>

              </li>

      </li>

        <li class="treeview"><a class="app-menu__item"  href = " javascript:void(0) " data-toggle="treeview"><i class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">Orders</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="{!! $url.'/orders' !!}"><i class="icon fa fa-circle-o"></i> Order List</a></li>
            <li><a class="treeview-item" href="{!! $url.'/orders/sales-order' !!}"><i class="icon fa fa-circle-o"></i> Sales order</a></li>
            <li><a class="treeview-item"  href = " javascript:void(0) "><i class="icon fa fa-circle-o"></i> Purchase order</a></li>
            <li><a class="treeview-item"  href = " javascript:void(0) "><i class="icon fa fa-circle-o"></i> Labelling order</a></li>
            <li><a class="treeview-item"  href = " javascript:void(0) "><i class="icon fa fa-circle-o"></i> loading order</a></li>
            <li><a class="treeview-item"  href = " javascript:void(0) "><i class="icon fa fa-circle-o"></i> Transfer order</a></li>
          </ul>
        </li>
		<li class="treeview"><a class="app-menu__item"  href = " javascript:void(0) " data-toggle="treeview"><i class="app-menu__icon fa fa-truck"></i><span class="app-menu__label">Shipping Document</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item"  href = " javascript:void(0) "><i class="icon fa fa-circle-o"></i> Packing List</a></li>
            <li><a class="treeview-item"  href = " javascript:void(0) "><i class="icon fa fa-circle-o"></i> Delivery document </a></li>
          </ul>
        </li>
		<li class="treeview"><a class="app-menu__item"  href = " javascript:void(0) " data-toggle="treeview"><i class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">Invoice</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item"  href = " javascript:void(0) "><i class="icon fa fa-circle-o"></i>CIA Invoice</a></li>
            <li><a class="treeview-item"  href = " javascript:void(0) "><i class="icon fa fa-circle-o"></i>CFL Invoice</a></li>
          </ul>
        </li>	
         <li><a class="app-menu__item" href="{!! $url.'/inventory-management' !!}"><i class="app-menu__icon fa fa-shopping-cart"></i><span class="app-menu__label">Inventory Management </span></a></li>
      </ul>
     
     
    </aside>
