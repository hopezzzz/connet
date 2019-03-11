@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-file-o"></i>Campaign Reports</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Campaign Reports</li>
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
                            <a href="{{ URL(config('app.admintemplatename').'/reporting') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <?php $userPlan = App\Model\UserPlan::where('user_id',$userID)->first(); ?>
                <div class="tile">
                    <div class="row">
                        <div class="col-md-6"><b>Customer Name:</b> {{ $userPlan->users->firstName }} {{ $userPlan->users->lastName }}</div>
                        <div class="col-md-6 text-right"><b>Total Plan Minutes:</b> {{ $userPlan->plans->minutes_per_month }}</div>
                    </div>
                    <div class="tile-body  table-responsive recordsTable">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Campaign Name</th>
                                    <th>Minutes Consumed</th>
                                    <th>Total Leads</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="show_records">
                            <?php $i=1; $totalMin=0; $totalCall=0; ?>
                              @if(isset($data))
                                @forelse($data->objects as $camps)
                                <tr>
                                    <td scope="col">{{ $i }}.</td>
                                    <td scope="col">{{ $camps->name }}</td>
                                    <td scope="col">{{ $min = $camps->total_called_seconds/60 }}</td>
                                    <td scope="col">{{ $call = $camps->total_calls_count }}</td>
                                    <?php $cmp = App\Model\Campaign::where('api_dept_id',$camps->id)->first(); ?>
                                    <td scope="col">
                                        <a href="@if(isset($cmp->id)) {{ URL('admin/campaign-leads/'.Crypt::encrypt($cmp->id)) }} @else href = " javascript:void(0) "@endif" data-toggle="tooltip" title="View Leads"><span><i class="fa fa-list"></i> </span></a>
                                    </td>
                                </tr>
                                <?php 
                                $totalCall += $call;
                                $totalMin +=$min; 
                                $i++; ?>
                                @empty
                                <tr>
                                  <td scope="col" colspan="4">No Record Found !</td>
                                </tr>
                                @endforelse
                                <tr>
                                    <th scope="col" colspan="2">Total Consumed Minutes </th>
                                    <th>{{ $totalMin }}</th>
                                    <th>{{ $totalCall }}</th>
                                    <th></th>
                                </tr>
                              @else
                              <tr>
                                <td scope="col" colspan="4">No Record Found !</td>
                              </tr>
                              @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Delete Modal -->
    <div id="confirm-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Delete</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to delete this record ?</h6>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary deleteRecordBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Ends -->
@endsection
