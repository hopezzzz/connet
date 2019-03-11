@extends('layouts.customer')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a  href = " javascript:void(0) ">Reporting</a>
      </li>

    </ol>
  </div>
  <div class="col-md-12">
      <div class="col-md-12">
        {{ Form::open(array('url'=>URL('customer/reporting'),'method'=>'GET','id'=>'lead_form')) }}
          <input id="tab-input" type="hidden" name="tab" value="@if(isset($tab)) {{$tab}} @endif">
          <input id="filter-input" type="hidden" name="filter" value="all">
          <div class="row reports-tabs">
            <div class="col-md-3">
              <label>Date From</label>
              <div class="input-group">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text calIcon"><i class="fa fa-calendar"></i></span>
                  </div>
                  {!! Form::text('date_from',null,['class'=>'form-control datepicker', 'id'=>'date1'])!!}
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <label>Date To</label>
              <div class="input-group">
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text calIcon"><i class="fa fa-calendar"></i></span>
                  </div>
                  {!! Form::text('date_to',null,['class'=>'form-control datepicker', 'id'=>'date2'])!!}
                </div>
              </div>
            </div>
            <div class="col-md-3 submit-class">
              {!! Form::submit('Submit',['class'=>'btn btn-primary'])!!}
              <a href="{{ URL('customer/reporting') }}" class="btn btn-primary">Reset</a>

            </div>

      </div>
      {{ Form::close() }}
      <!-- Nav tabs -->
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="leadTabs nav-link @if(isset($tab)) @if($tab=='min_consumed') active show @endif @else active @endif" data-toggle="tab" href="#min_consumed">Campaign Report</a>
        </li>

        <li class="nav-item">
          <a class="leadTabs nav-link @if(isset($tab) && $tab=='lead_summary') active show @endif" data-toggle="tab" href="#lead_summary">Leads Summary</a>
        </li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div id="min_consumed" class="tab-pane @if(isset($tab)) @if($tab=='min_consumed') active @endif @else active @endif">
          <div class="leadRecord">
            <?php $userPlan = App\Model\UserPlan::where('user_id',\Auth::user()->id)->first(); ?>
            <div><h6 align="right"> <b>Total Plan Minutes: {{ $userPlan->plans->minutes_per_month }}</b></h6></div>

            <div class="table table-striped table-hover">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Sr.</th>
                    <th scope="col">Campaign Name</th>
                    <th scope="col">Minutes Consumed</th>
                    <th scope="col">No. of Leads</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; ?>
                  @if(isset($data))
                    @forelse($data->objects as $camps)
                    <tr>
                      <td scope="col">{{ $i }}.</td>
                      <td scope="col">{{ $camps->name }}</td>
                      <td scope="col">{{ $camps->total_called_seconds/60 }}</td>
                      <td scope="col">{{ $camps->total_calls_count }}</td>
                      <td scope="col">
                        <?php $cmp = App\Model\Campaign::where('api_dept_id',$camps->id)->first(); ?>
                        <a href="{{URL('customer/campaign-leads')}}/{{ Crypt::encrypt($cmp->id) }}"><i class="fa fa-list" data-toggle="tooltip" data-placement="top" title="Leads"></i></a>
                      </td>
                    </tr>
                    <?php $i++; ?>
                    @empty
                    <tr>
                      <td scope="col" colspan="5">No Record Found !</td>
                    </tr>
                    @endforelse
                  @else
                  <tr>
                    <td scope="col" colspan="5">No Record Found !</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Leads Summary -->
        <div id="lead_summary" class="tab-pane @if(isset($tab) && $tab=='lead_summary') active @endif">
          <div class="recordsTable leadRecord">
            <div class="form-group md-form text-right col-md-12">
              <div class="weekDays-selector leadSummary">
                 <input id="weekday-all" class="weekday lead_filter" type="radio" name="filter" value="all" @if($filter=='all' || !isset($filter)) checked="checked" @endif><label for="weekday-all">All</label>

                 <input id="weekday-placed" class="weekday lead_filter" type="radio" name="filter" value="placed" @if($filter=='placed') checked="checked" @endif><label for="weekday-placed">Placed</label>

                 <input id="weekday-unplaced" class="weekday lead_filter" type="radio" name="filter" value="unplaced" @if($filter=='unplaced') checked="checked" @endif><label for="weekday-unplaced">Unplaced</label>

                 <input id="weekday-invalid" class="weekday lead_filter" type="radio" name="filter" value="invalid" @if($filter=='invalid') checked="checked" @endif><label for="weekday-invalid">Invalid</label>
              </div>
            </div>
            <div class="recordsTable">


            <div class="table table-striped table-hover">
              <table class="table" width="100%">
                <thead>
                  <tr>
                    <th scope="col">Sr.</th>
                    <th scope="col" width="15%">Campaign Name</th>
                    <th scope="col" width="10%">Contact No</th>
                    <th scope="col" width="50%">Call Script</th>
                    <th scope="col" width="20%">Dated</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i=1; ?>
                @if(isset($leads))
                    @forelse($leads as $lead)
                    <tr>
                      <td scope="col">{{ $i }}.</td>
                      <td scope="col">{{ $lead->campaign_details->title }}</td>
                      <td scope="col">{{ $lead->mobileNo }}</td>
                      <td scope="col">{{ $lead->callScript }}</td>
                      <td scope="col">{{ date('m-d-Y h:i:s A', strtotime($lead->created_at)) }}</td>
                    </tr>
                    <?php $i++; ?>
                    @empty
                    <tr>
                      <td scope="col" colspan="5">No Record Found !</td>
                    </tr>
                    @endforelse
                  @else
                  <tr>
                    <td scope="col" colspan="5">No Record Found !</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
            {{ $leads->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
