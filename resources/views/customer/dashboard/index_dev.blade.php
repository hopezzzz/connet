
@extends('layouts.customer')
@section('content')
<script>
var monArr = [];
var recArr = [];
var campArr = [];
var countArr = [];
</script>
<?php
$totalLeads = 0;
$months = array('January','February','March','April','May','June','July ','August','September','October','November','December');
foreach($chart['lead_chart'] as $ch){ ?>
  <script>monArr.push('<?php echo $months[$ch->month-1]; ?>');</script>
  <script>recArr.push('<?php echo $ch->count; ?>');</script>
  <?php 
}
$strCamp = '';
foreach ($chart['camp_chart'] as $ch) { ?>
  <script>campArr.push('<?php echo htmlspecialchars($ch->title); ?>');</script>
  <script>countArr.push('<?php echo $ch->count; ?>');</script>
  <?php
  $totalLeads += $ch->count;
}
?>
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a  href = " javascript:void(0) ">Dashboard</a>
        </li>

      </ol>
      <!-- Icon Cards-->
      <div class="row">
        <div class="col-xl-4 col-sm-4 mb-3">
          <div class="card text-white bg-primary o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
              <i class="fa fa-file" aria-hidden="true"></i>
              </div>
              <div class="mr-5">Reports</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{ URL('customer/reporting') }}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-4 col-sm-4 mb-3">
          <div class="card text-white bg-warning o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
                  <i class="fa fa-bullhorn" aria-hidden="true"></i>
              </div>
              <div class="mr-5">
			  Campaigns</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{ URL('customer/campaigns') }}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
        <div class="col-xl-4 col-sm-4 mb-3">
          <div class="card text-white bg-success o-hidden h-100">
            <div class="card-body">
              <div class="card-body-icon">
               <i class="fa fa-shopping-cart" aria-hidden="true"></i>
              </div>
              <div class="mr-5">Plans</div>
            </div>
            <a class="card-footer text-white clearfix small z-1" href="{{ URL('customer/plans') }}">
              <span class="float-left">View Details</span>
              <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
            </a>
          </div>
        </div>
      </div>
      <!-- Area Chart Example-->

      <div class="row">
        <?php if($totalLeads!=0){ ?>
        <div class="col-lg-8">
          <!-- Example Bar Chart Card-->
          <div class="mb-3">
            <div class="card-header">
              <i class="fa fa-bar-chart"></i> Lead Chart </div>
            <div class="card-body main-chart">
              
              <div class="row">
                <div class="col-sm-8 my-auto"><div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                  <canvas id="myBarChart" width="467" height="233" style="display: block; height: 292px; width: 584px;" class="chartjs-render-monitor"></canvas>
                </div>
                <div class="col-sm-4 text-center my-auto">
                  <div class="h4 mb-0 text-primary">{{ $totalLeads }}</div>
                  <div class="small text-muted">Total Leads</div>
                  <hr>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <!-- Example Pie Chart Card-->
          <div class="mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Campaigns Chart</div>
            <div class="card-body main-chart"><div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
              <canvas id="myPieChart" width="327" height="327" style="display: block; height: 409px; width: 409px;" class="chartjs-render-monitor"></canvas>
            </div>
          </div>
		  </div>
      <?php }?>
		  </div>
      <!-- Example DataTables Card-->
    </div>
	</div>
  @endsection
