@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-globe"></i> Campaign Details</h1>
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
                        <div class="form-group text-right">
                            <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="tile row">
                  <?php $dayArr = array('mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'); ?>
                  <div class="col-md-12">
                        <h2>Campaign Details</h2>
                        <div class="col-md-12">
							<table>
							@if(isset($userCamp->title))
								<tr>
									<td><span class="details-user"><b>Title :</b></span></td> 
									<td><span class="details_now"> {{ $userCamp->title }}</span></td>
								</tr>
							@endif
							@if(isset($userCamp->email))
								<tr>
									<td><span class="details-user"><b>Email Address :</b></span></td>
									<td><span class="details_now"> {{ $userCamp->email }}</span></td>
								</tr>
							@endif
							@if(isset($userCamp->template))
								<tr>
									<td><span class="details-user"><b>Template :</b></span></td>
									<td><span class="details_now"> {{ $userCamp->template }}</span></td>
								</tr>
							@endif
							@if(isset($userCamp->country_id) && $userCamp->country_id!=0)
								<tr>
									<td><span class="details-user"><b>Country :</b></span></td>
									<td><span class="details_now"> {{ $userCamp->coutry_details->countryName }}</span></td>
								</tr>
							@endif
							@if(isset($userCamp->available_days))
								<tr>
									<td><span class="details-user"><b>Available Days :</b></span></td>
									<td><span class="details_now">
									<?php $days = json_decode($userCamp->available_days); ?>
									@foreach($days as $day)
										{{ $dayArr[$day] }}
									@endforeach
									</span></td>
								</tr>
							@endif
							<?php $availHours = json_decode($userCamp->available_hours); ?>
							@if(isset($userCamp->available_hours))
								<tr>
									<td><span class="details-user"><b>Available Hours :</b></span></td>
									<td><span class="details_now"> {{ $availHours->from }} - {{ $availHours->to }}</span></td>
								</tr>
							@endif
							<?php $breakHours = json_decode($userCamp->break_hours); ?>
							@if(isset($userCamp->break_hours))
								<tr class="d-none">
									<td><span class="details-user"><b>Break Hours :</b></span></td>
									<td><span class="details_now"> {{ $breakHours->from }} - {{ $breakHours->to }}</span></td>
								</tr>
							@endif
							<tr><td><span class="details-user"><b>Campaign Contacts :</b></span></td><td></td></tr>
							</table>
							<table class="table">
								<thead>
									<tr>
										<th>Name</th>
										<th>Phone Number</th>
									</tr>
								</thead>
								<tbody>
									@forelse($userCamp->campaigns_contacts as $ct)
									<tr>
										<td>{{ $ct->contact->name }}</td>
										<td>{{ $ct->contact->contact }}</td>		
									</tr>
									@empty
									<tr><td colspan="2">No Record Found !</td></tr>
									@endforelse
								</tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
