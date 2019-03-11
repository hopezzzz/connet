
@extends('layouts.customer')
@section('content')

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
              <div class="mr-5">Campaigns</div>
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
              <div class="mr-5">Plans & Billing</div>
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

        <div class="col-lg-8">
          <!-- Example Bar Chart Card-->

          <div class="mb-3">
            <div class="card-header">
              <i class="fa fa-bar-chart"></i> Lead Chart </div>
            <div class="card-body main-chart">
              <div class="row">
                <div class="col-md-2">

                </div>
                <div class="col-md-3">
                  <input type="text" placeholder="Start From" name="startDate" id="date1" class="form-control datepicker">
                </div>
                <div class="col-md-3">
                  <input type="text" placeholder="Date To" name="endDate" id="date2" class="form-control datepicker">
                </div>
                <div class="col-md-2">
                  <label for=""></label>
                  <input type="button" id="showChart" class="btn btn-primary" value="Show">
                </div>
      				<div class="col-md-2">
      				</div>
              </div>
              <div class="row">
                <div class="col-sm-8 my-auto"><div style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;" class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                  <canvas id="myBarChart" width="467" height="233" style="display: block; height: 292px; width: 584px;" class="chartjs-render-monitor"></canvas>
                </div>
                <div class="col-sm-4 text-center my-auto">
                  <div class="h4 mb-0 text-primary totalLeads"></div>
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

		  </div>
      <!-- Example DataTables Card-->
    </div>
	</div>

  <script type="text/javascript">
  $(document).ready(function(){
    jQuery(document).on('click','#showChart',function(eve) {
      let startDate = $('input[name="startDate"]').val();
      let endate =  $('input[name="endDate"]').val();
      if($.trim(startDate).length > 0 && $.trim(endate).length == 0){
        tost('Please select Date to and submit request again.','Error',1000);
        return false;
      }
      $.ajax({
        url: "{{url('customer/get-charts')}}",
        method: "POST",
        data : {startDate : startDate , endDate : endate },
        beforeSend : function () {
          $('.loader_div').show();
          if (myLineChart != null) { myLineChart.destroy(); myPieChart.destroy();}
        },
        complete : function () {
          $('.loader_div').hide();
        },
        success: function(data) {
          var month = [];
          var count = [];
          var pieCampName = []
          var pieCount = []

          $('.totalLeads').html(data.lead_count)


          if (data.lead_count !=0 ) {
            for (var i = 0; i < data.camp_chart.length; i++) {
               month.push(data.camp_chart[i].monthname);
               count.push(data.camp_chart[i].lead_count);
            }
            for (var i = 0; i < data.campChart.length; i++) {
               pieCampName.push(data.campChart[i].title);
               pieCount.push(data.campChart[i].count);
            }
          }else{
            month = ['No Record'];
            count = [0];
            pieCampName = ['No Record'];
            pieCount = [0];

            if (eve.originalEvent != undefined) {
                $.toast({
              heading             : 'Warning',
              text                : 'No Data found please try with new entries.',
              loader              : true,
              loaderBg            : '#fff',
              showHideTransition  : 'fade',
              icon                : 'warning',
              hideAfter           : 1500,
              position            : 'top-right'
            });

            }

          }





          var ctx = document.getElementById("myBarChart");
          window.myLineChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: month,
              datasets: [{
                label: "Lead",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: count,
              }],
            },
            options: {
              scales: {
                xAxes: [{
                  time: {
                    unit: 'month'
                  },
                  gridLines: {
                    display: false
                  },
                  ticks: {
                    maxTicksLimit: 6
                  }
                }],
                yAxes: [{
                  ticks: {
                    min: 0,
                    max: Math.max(...count),
                    maxTicksLimit: 5
                  },
                  gridLines: {
                    display: true
                  }
                }],
              },
              legend: {
                display: false
              }
            }
          });


          var ctx1 = document.getElementById("myPieChart");
          window.myPieChart = new Chart(ctx1, {
            type: 'pie',
            data: {
              labels: pieCampName,
              datasets: [{
                data: pieCount,
                backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745','#FF6633',
                                  '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6','#6666FF',
                            		  '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
                            		  '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A',
                            		  '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
                            		  '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC',
                            		  '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
                            		  '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680',
                            		  '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
                            		  '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3',
                            		  '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6',
                                ],
              }],
            },
          });

        },
        error: function(data) {
          console.log(data);
        }
      });
    })
    $('#showChart').trigger('click');
  });

  </script>


  @endsection
