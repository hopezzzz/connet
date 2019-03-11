@extends('layouts.customer')
@section('content')
@if(\Auth::user()->call_settings)
  @php $recDisplay = \Auth::user()->call_settings->call_recording_display; @endphp
@else
  @php $recDisplay = 1; @endphp
@endif

<div class="content-wrapper">
<div class="container-fluid">
<!-- Breadcrumbs-->
<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a  href = " javascript:void(0)">Campaign Leads</a>
  </li>

</ol>

<!-- Icon Cards-->
<div class="row">
  <div class="col-md-12">
    {{ Form::open(array('url'=>url()->current(),'method'=>'GET')) }}
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
        <a href="{{ url()->current() }}" class="btn btn-primary">Reset</a>
      </div>
      {{ Form::close() }}
    </div>
    <div class="clearfix"></div>
    <div style="margin: 10px auto; padding: 0;" class="col-md-12 pull-left text-left clearfix">
    <b>Campaign Name:</b> {{ $campData->title }}
    </div>
    <div style="margin: 10px auto; padding: 0;" class="col-md-4 pull-left text-left clearfix">
    <input type="text" data-url="{{ url('customer/campaign-leads/'.Crypt::encrypt($campData->id)) }}" class="form-control" id="searchKey" placeholder="Search Leads by ID, Agent Name">
    </div>
    <div style="margin: 10px auto; padding: 0;" class="col-md-4 pull-right text-right clearfix">
      @if($recDisplay==1)
        <a class="btn btn-primary red-color" href="{{ URL('export-lead-data') }}/{{ $campData->id}}">Download</a>
      @endif
      <a class="btn btn-primary" href="{{ url()->previous() }}">Back</a>
    </div>

    <div class="table-responsive recordsTable">
      <div class="table table-hover table-bordered">
        <table class="table" width="100%">
          <thead>
            <tr>
              <th scope="col" width="6%">Sr.</th>
              <th scope="col" width="7%">Lead ID</th>
              <th scope="col" width="9%">Call Length</th> 
              <th scope="col" width="15%">Call Date</th>
              <th scope="col" width="12%">Agent Name</th>
              @if($recDisplay==1)<th scope="col" width="20%">Call Summary</th>@endif
              <th scope="col" width="10%">Rating</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php $i=0; ?>
          @forelse($leadsData as $leads)
          @php
          $i++;
          $length = $leads->call_length/60;
          @endphp
          <tr id="{{ $leads->id }}">
              <td>{{ $i }}</td>
              <td>{{ $leads->lead_id }}</td>
              <td><span class="blue-color"><i data-toggle="tooltip" data-placement="top" title="Total Call Length: {{ $length }} Minute(s)" class="fa fa-info-circle"></i></span></td>
              <td>{{ date("m-d-Y h:i:s A", strtotime($leads->startdate)) }}</td>
              <td>@if($leads->agent) {{ $leads->agent }} @else N/A @endif</td>
              @if($recDisplay==1)
              <td>
                  <audio controls>
                      <source src="{{ $leads->recording }}" type="audio/wav">
                  </audio>

              </td>
              <td class="rateTD" title="@if($leads->rating==1) Poor @elseif($leads->rating==2) Good @elseif($leads->rating==3) Excellent @endif">
              @for($i=1; $i<=3; $i++)
                @if($i<=$leads->rating) 
                  <i class="stars rate-blue fa fa-star"></i>
                @else
                  <i class="stars fa fa-star-o"></i>
                @endif  
              @endfor
            </td>
              @endif
              <td>
                <a class="blue-color getLeadData" data-url="{{ URL('get-lead-data') }}/{{ $leads->id }}" href="javascript:void(0)" data-toggle="modal" data-target="#leadDetails"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View Lead Details"></i></a>
                &nbsp;
                @if($recDisplay==1)
                  @if(strpos($leads->recording , 'recordings/False' ) === false ) <a href="{{$leads->recording}}" download="{{$leads->recording}}"><i data-toggle="tooltip" data-placement="top" title="Download Lead" class="fa fa-download" aria-hidden="true"></i></a>@endif 
                @endif
                <a class="blue-color rateLead" data-lead-id="{{ Crypt::encrypt($leads->id) }}" href="javascript:void(0)" data-toggle="modal" data-target="#leadRating"><i class="fa fa-star" data-toggle="tooltip" data-placement="top" title="View Lead Rating"></i></a>
              </td>
          </tr>
          @empty
          <tr>
              <td colspan="7">No Record Found !</td>
          </tr>
          @endforelse
          </tbody>
        </table>
        {{ $leadsData->links() }}
      </div>
    </div>
  </div>

</div>
</div>
</div>
<div id="leadDetails" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Lead Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>
<!-- Lead Rating Modal -->
<div id="leadRating" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Lead Rating</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h6>Rate this call between 1 to 3 stars.</h6>
        <fieldset class="rating">
            <input type="radio" id="star3" name="rating" value="3" />
            <label class = "full" for="star3" title="Excellent"></label>
            <input type="radio" id="star2" name="rating" value="2" />
            <label class = "full" for="star2" title="Good"></label>
            <input type="radio" id="star1" name="rating" value="1" />
            <label class = "full" for="star1" title="Poor"></label>
        </fieldset>
        <input type="hidden" id="rateLeadID" value="">
      </div>
    </div>
  </div>
</div>
@endsection
