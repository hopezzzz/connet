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
          			<a class="btn btn-primary" href="{{URL('customer/profile')}}">Back</a>
        		</div>
        	</div>
	    </div>
  		<div>
  			{{ Form::model($data,array('url'=>'customer/update-profile', 'method'=>'post', 'id'=>'profile_form')) }}
	  			<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="yourName">First Name </label>
							{{ Form::text('fname',null,['class'=>'form-control']) }}
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="form-group">
							<label for="yourName">Last Name </label>
							{{ Form::text('lname',null,['class'=>'form-control']) }}
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="phone">Phone Number </label>
							{{ Form::text('phone',null,['class'=>'form-control']) }}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="companyname">Company Name </label>
							{{ Form::text('companyName',null,['class'=>'form-control']) }}
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="companyurl">Company URL </label>
							{{ Form::text('companyUrl',null,['class'=>'form-control']) }}
						</div>
					</div>
				</div>
				<div class="row">
	            	<div class="col-md-12 text-right">
	                  	<input class="btn btn-primary" type="submit" value="Update">
	            	</div>
	          	</div>
          	{{ Form::close() }}
  		</div>
    </div>
</div>
@endsection
