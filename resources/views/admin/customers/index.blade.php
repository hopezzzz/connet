@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-users"></i> Customers</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Customers</li>
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
                        <div class="form-group pull-left  col-md-4 nopadding">
                            <input type="text" data-url="{{url('admin/customers')}}" class="form-control" id="searchKey" placeholder="Search Customer by Name">
                        </div>
<!--                         <div class="form-group text-right">
                            <a href="{{ URL(config('app.admintemplatename').'/add-new-plan') }}" class="btn btn-primary">Add Plan</a>
                        </div> -->
                    </div>
                </div>
                <div class="tile">
                    <div class="text-right">
                        <a href="javascript:void(0)" class="btn btn-primary active">On Board Customer </a> 
                        <a href="{{ URL('admin/off-customers') }}" class="btn btn-primary">Off Board Customer</a>
                    </div>    
                    <div class="tile-body table-responsive recordsTable">
                        <table class="table  table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Plan Name</th>
                                    <th>Region</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="show_records">
                            <?php $i=0; ?>
                            @forelse($customers as $cust)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>@if(isset($cust->firstName)){{ ucfirst($cust->firstName) }} @endif @if(isset($cust->lastName)){{ ucfirst($cust->lastName) }} @endif</td>
                                    <td>@if(isset($cust->email)){{ $cust->email }} @endif</td>
                                    <td>@if(isset($cust->phoneNo)){{ $cust->phoneNo }} @endif</td>
                                    <td>@if(isset($cust->user_plan->plans->name)){{ $cust->user_plan->plans->name }} @endif</td>
                                    <td>@if(isset($cust->region->name)){{ $cust->region->name }} @endif</td>
                                    <td>
                                        <a href="{{ $url.'/view-customer/'.Crypt::encrypt($cust->id) }}" data-toggle="tooltip" title="View"><span><i class="fa fa-eye"></i> </span></a>
                                        @if(count($cust->user_campaigns) > 0)
                                        <a href="{{ $url.'/customer-campaign/'.Crypt::encrypt($cust->id) }}"data-toggle="tooltip" title="Campaign"><span><i class="fa fa-newspaper-o"></i> </span></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No Records Found !</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{$customers->links()}}
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
