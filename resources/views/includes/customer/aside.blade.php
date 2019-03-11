<div class="collapse navbar-collapse" id="navbarResponsive">
  <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Dashboard">
      <a class="nav-link" href="{{ URL('customer/dashboard') }}">
        <i class="fa fa-user"></i>
        <span class="nav-link-text">Dashboard</span>
      </a>
    </li>
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Purchase History">
      <a class="nav-link" href="{{ URL('customer/purchase') }}">
        <i class="fa fa-history" aria-hidden="true"></i>
        <span class="nav-link-text">Purchase History</span>
      </a>
    </li>
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Plans &amp; Billing">
      <a class="nav-link" href="{{ URL('customer/plans') }}">
        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        <span class="nav-link-text">Plans &amp; Billing</span>
      </a>
    </li>
  <!-- <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Charts">
      <a class="nav-link" href="javascript:void(0)">
      <i class="fa fa-users" aria-hidden="true"></i>
        <span class="nav-link-text">Leads</span>
      </a>
    </li> -->
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Campaigns">
      <a class="nav-link" href="{{ URL('customer/campaigns') }}">
        <i class="fa fa-bullhorn" aria-hidden="true"></i>
        <span class="nav-link-text">Campaigns</span>
      </a>
    </li>
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Contacts">
      <a class="nav-link" href="{{ URL('customer/contacts') }}">
        <i class="fa fa-phone" aria-hidden="true"></i>
        <span class="nav-link-text">Contacts</span>
      </a>
    </li>
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Reports">
      <a class="nav-link" href="{{ URL('customer/reporting') }}">
       <i class="fa fa-file-text" aria-hidden="true"></i>
        <span class="nav-link-text">Reports</span>
      </a>
    </li>
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Settings">
      <a class="nav-link" href="{{ URL('customer/setting') }}">
        <i class="fa fa-cogs"></i>
        <span class="nav-link-text">Settings</span>
      </a>
    </li>
    <!-- <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Profile">
      <a class="nav-link" href="{{ URL('customer/profile') }}">
        <i class="fa fa-user"></i>
        <span class="nav-link-text">Profile</span>
      </a>
    </li> -->
  </ul>
  <ul class="navbar-nav sidenav-toggler">
    <li class="nav-item">
      <a class="nav-link text-center" id="sidenavToggler">
        <i class="fa fa-fw fa-angle-left"></i>
      </a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto">
    @if(Auth::user()->status != 1 )

    <?php
      $message = 'User inactive payment not recived';
      if (Auth::user()->status == 2) {
        $message = 'You are purchased trail plan and your mintue consumed';
      }

      if (Auth::user()->status == 0) {
        $message = 'User inactive please check your subscription status';
      }

      if (Auth::user()->status == 9) {
        $message = 'User inactive payment not received';
      }
    ?>
    <li class="nav-item">
      <a class="nav-link mr-lg-2" href="javascript:void(0)">
          <i class="fa fa-info-circle Blink" data-toggle="tooltip" data-placement="top" data-original-title="{{$message}}"></i>

      </a>
    </li>
    @endif
    <li class="nav-item">
      <a class="nav-link mr-lg-2" href="javascript:void(0)">
          <i class="fa fa-money" data-toggle="tooltip" data-placement="top" data-original-title="Balance Amount: {{Auth::user()->region->countries->currencySymbol}} {{ Auth::user()->balanceAmount }}"></i>
        <span class="profile-text">Balance Amount </span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link mr-lg-2" href="{{ URL('customer/profile') }}">
        <i class="fa fa-user"></i>
        <span class="profile-text">Profile</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link mr-lg-2" href="{{ route('logout') }}">
        <i class="fa fa-fw fa-sign-out"></i>Logout
      </a>
    </li>
  </ul>
</div>
</nav>
