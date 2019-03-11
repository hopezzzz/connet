@extends('layouts.customer')
@section('content')
<div class="content-wrapper">
  	<div class="container-fluid">
	    <!-- Breadcrumbs-->
	    <ol class="breadcrumb">
	      <li class="breadcrumb-item">
	        <a href="javascript:void(0)">Profile</a>
	      </li>

	    </ol>
	    <div class="row">
	    	<div class="col-md-12">
	    		<div style="margin: 10px auto;padding: 0;" class="col-md-4 pull-right text-right clearfix">
          			<a data-toggle="tooltip" title="" data-original-title="Edit" class="btn btn-primary btn-round" href="{{URL('customer/edit-profile')}}"><i class="fa fa-pencil"></i></a>
        		</div>
        	</div>
	    </div>
  		<div class="profile-detail">
  			<div class="row">
				<div class="col-md-4">
					<div class="form-group md-form">
						<label for="firstName">First Name </label>
						<span>@if(isset($data->firstName)) {{ $data->firstName }} @endif </span>
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="form-group md-form">
						<label for="lastName">Last Name </label>
						<span>@if(isset($data->lastName)) {{ $data->lastName }} @else N/A @endif</span>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group md-form">
						<label for="email">Email</label>
						<span>@if(isset($data->email)) {{ $data->email }} @endif </span>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-md-4">
					<div class="form-group md-form">
						<label for="phone">Phone Number </label>
						<span>@if(isset($data->phoneNo)) {{ $data->phoneNo }} @endif</span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group md-form">
						<label for="companyname">Company Name </label>
						<span>@if(isset($data->companyName)) {{ $data->companyName }} @else N/A @endif</span>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="col-md-4">
					<div class="form-group md-form">
						<label for="companyurl">Company URL </label>
						<span>@if(isset($data->companyUrl)) {{ $data->companyUrl }} @else N/A @endif</span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group md-form">
						<label for="country">Region </label>
						<span>@if(isset($data->region->name)) {{ $data->region->name }} @endif</span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group md-form">
						<label for="country">Country </label>
						<span>@if(isset($data->region->countries->countryName)) {{ $data->region->countries->countryName }} @endif</span>
					</div>
				</div>
			</div>
  		</div>
    </div>
</div>
@endsection
