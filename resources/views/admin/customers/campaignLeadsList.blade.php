@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-globe"></i> Campaigns Leads</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="">Customers</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        @if ($message = Session::get('flash_message'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <div class="form-group text-right">
                            <a href="@if(isset($_SERVER['HTTP_REFERER'])) {{ $_SERVER['HTTP_REFERER'] }} @else {{ URL(config('app.admintemplatename').'/customer-campaign/'.Crypt::encrypt($campData->user_detail->id)) }} @endif" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="tile">
                    <div class="row">
                        <div class="text-left col-md-6">
                            <p style="font-size:16px;"><b>Customer Name :</b> {{ $campData->user_detail->firstName }} {{ $campData->user_detail->lastName }}</p>
                        </div>
                        <div class="text-right col-md-6">
                            <p style="font-size:16px;"><b>Campaign :</b> {{ $campData->title }}</p>
                        </div>
                    </div>
                    <div class="tile-body table-responsive recordsTable">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Lead ID</th>
                                    <th>Call Length</th>
                                    <th>Call Date</th>
                                    <th>Call Summary</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="show_records">
                            <?php $i=0; ?>
                            @if(isset($leadsData))
                                @forelse($leadsData as $leads)
                                @php  
                                    $i++;
                                    $length = $leads->cost/60;  
                                @endphp
                                <tr>
                                  <td>{{ $i }}</td>
                                  <td>{{ $leads->lead_id }}</td>
                                  <td><span class="blue-color"><i data-toggle="tooltip" data-placement="top" title="Total Call Length: {{ $length }} Minute(s)" class="fa fa-info-circle"></i></span></td>
                                  <td>{{ date("m-d-Y h:i:s A", strtotime($leads->startdate)) }}</td>
                                  
                                  <td>
                                      <audio controls>
                                          <source src="{{ $leads->recording }}" type="audio/wav">
                                      </audio>

                                  </td>
                                  
                                  <td>
                                    <a class="blue-color getLeadData" data-url="{{ URL('get-lead-data') }}/{{ $leads->id }}" href="javascript:void(0)" data-toggle="modal" data-target="#leadDetails"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View Lead Details"></i></a>
                                    &nbsp;
                                    <a href="{{$leads->recording}}" download="{{$leads->recording}}"><i data-toggle="tooltip" data-placement="top" title="Download Lead" class="fa fa-download" aria-hidden="true"></i></a>
                                  </td>
                              </tr>
                              @empty
                              <tr>
                                  <td colspan="6">No Record Found !</td>
                              </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="6">No Record Found !</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{ $leadsData->links() }}

                    </div>
                </div>
            </div>
        </div>
    </main>
    <div id="leadDetails" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Lead Details</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
@endsection
