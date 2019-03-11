@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-list"></i> Plans</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a  href = " javascript:void(0) ">Plans</a></li>
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
                            <a href="{{ URL(config('app.admintemplatename').'/plans') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="tile">
                    <h2>{{$planData->name}}</h2>
                    <h5>Description</h5>
                    <p>{{$planData->description}}</p>
					<div class="row">
					<div class="col-md-12">
					<table>
					   <tr>
					       <td><span class="details-user"><b>Minutes/month :</b></span></td>
					       <td><span class="details_now">{{$planData->minutes_per_month}}</span></td>
					   </tr>
					   <tr>
					       <td><span class="details-user"><b>Leads/month :</b></span></td>
					       <td><span class="details_now">{{$planData->leads_per_month}}</span></td>
                        </tr>
                        <tr>
                           <td><span class="details-user"><b>Plan Duration :</b></span></td>
                           <td><span class="details_now">{{$planData->duration}} Month</span></td>
                        </tr>
                        <tr>
                           <td><span class="details-user"><b>Plan Type :</b></span></td>
                           <td><span class="details_now">{{$planData->billing_Type->planType}}</span></td>
                        </tr>
                        <tr>
                           <td><span class="details-user"><b>No of contacts :</b></span></td>
                           <td><span class="details_now">{{$planData->no_of_contacts}}</span></td>
                        </tr>
					</table>
					</div>

				   <div class="col-md-12">
                    <h5>Plan Prices</h5>
					</div>
					<div class="col-md-12">
                    <table class="table table-hover table-bordered">
                    <tr>
			           <th>Currency</th><th>Amount</th>
                       @if($planData->billingType == 2 )
                       <th>Credit</th>
                       <th>Cost Per Minute</th>
                       @endif
			        </tr>
                    @forelse($planData->plan_prices as $price)
                        <tr>
                            <td><b>{{ $price->plan_currency->currencyCode }} ({{$price->plan_currency->currencySymbol}})</b></td>
                            <td>{{$price->amount}}</td>
                            @if($planData->billingType == 2 )
                            <td>{{$price->credit}}</td>
                            <td>{{$price->per_min_cost}}</td>
                            @endif
                        </tr>
                        @empty
                        <tr><td>No Record Found.</td></tr>
                    @endforelse
                    </table>
					</div>
					<div class="col-md-12">
                    <h5>Features</h5>
					</div>
					<div class="col-md-12">
                    <p>{{$planData->features}}</p>
					</div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Modal -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Plan</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- Add Model Ends -->
@endsection
