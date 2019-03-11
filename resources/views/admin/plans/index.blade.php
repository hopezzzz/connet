@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-list"></i> Plans</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Plans</li>
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
                            <input type="text" data-url="{{url('admin/plans')}}" class="form-control" id="searchKey" placeholder="Search Plans by Name">
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ URL(config('app.admintemplatename').'/add-new-plan') }}" class="btn btn-primary">Add Plan</a>
                        </div>
                    </div>
                </div>
                <div class="tile">
                    <div class="tile-body   table-responsive recordsTable">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Plan Name</th>
                                    <th>Minutes/Month</th>
                                    <th>Leads/Month</th>
                                    <th>Prices</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="show_records">
                            <?php $i=0; ?>
                            @forelse($plans as $plan)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->minutes_per_month }}</td>
                                    <td>{{ $plan->leads_per_month }}</td>
                                    <td>
                                    <?php $planPrices = ''; ?>
                                    @foreach($plan->plan_prices as $price)
                                        <?php $planPrices .= $price->plan_currency->currencyCode.' '.$price->amount.', '; ?>
                                    @endforeach
                                    {{ rtrim($planPrices,', ') }}
                                    </td>
                                    <td>
                                        <a href="{{ $url.'/edit-plan/'.Crypt::encrypt($plan->id) }}" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                                        <a href="{{ $url.'/view-plan/'.Crypt::encrypt($plan->id) }}" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
                                        <a class="deleteRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-delete-modal" id="{{ $plan->id }}" data-table="{{ Crypt::encrypt('plans') }}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i></a></td>
                                </tr>
                            @empty
                            	<tr>
                            		<td colspan="6">No Records Found !</td>
                            	</tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{$plans->links()}}
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
                        <button type="button" class="btn btn-primary cancel-now" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary deleteRecordBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Ends -->
@endsection
