@extends('layouts.app')
@section('content')

<div class="">
	<div class="main-wrapper">
		<div><center><h2>Register Now</h2></center></div>
		<div class="choose-sec">
			<div class="card">
				<div class="card-body mb-4">
					<!-- Stepper -->
					<div class="steps-form">
						<div class="steps-row setup-panel">
							<div class="steps-step">
								<a href="#step-1" class="btn btn-indigo btn-circle"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/your-detail-h.png') }}"/></a>
								<p>Fill Details</p>
							</div>
							<div class="steps-step">
								<a href="#step-2" class="btn btn-default btn-circle"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/plan-h.png') }}"/></a>
								<p> Choose Plan</p>
							</div>
							<div class="steps-step">
								<a href="#step-3" class="btn btn-default btn-circle" disabled="disabled"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/payment-h.png') }}"/></a>
								<p> Your Payment</p>
							</div>
							<div class="steps-step">
								<a href="#step-4" class="btn btn-default btn-circle" disabled="disabled"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/register-h.png') }}"/></a>
								<p>Complete</p>
							</div>
						</div>
					</div>
					{{ Form::open(array('url' => '/registeration','id' => 'client-signup-form','class' => 'client-signup-form','autocomplete' => 'off')) }}
						<!-- First Step -->
						<div class="row setup-content" id="step-1">
							<div class="col-md-12">
								<h3>Fill Details</h3>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group md-form">
											<label for="yourName">First Name <span class="star">*</span></label>
											<input type="hidden" id="_token" name="remember_token" value="{{ csrf_token() }}"/>
											<input type="hidden" id="userIDCheck" name="userIDCheck" value="" class="userIDCheck"/>
											<input type="hidden" class="formStep" name="formStep" value=""/>
											{!!  Form::text('firstName',null,['placeholder'=>"First Name",'class'=>'form-control']); !!}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="yourLastName" >Last Name </label>
											{!!  Form::text('lastName',null,['placeholder'=>"Last Name",'class'=>'form-control']); !!}
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="yourEmail" >Email <span class="star">*</span></label>
											{!!  Form::text('email',null,['placeholder'=>"Email",'class'=>'form-control checkEmail']); !!}
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-3">
										<div class="form-group md-form">
											<label for="yourPassword" >Password <span class="star">*</span></label>
											<input type="password" autocomplete="off" placeholder="Password" id="password" name="password" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group md-form">
											<label for="confirmPassword" >Confirm Password <span class="star">*</span> </label>
											<input type="password" autocomplete="off" placeholder="Confirm Password" id="confirmPassword" name="confirmPassword" class="form-control">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group md-form">
											<label for="yourLastName" >Phone Number <span class="star">*</span></label>
											{!!  Form::text('phoneNo',null,['placeholder'=>"Phone Number",'class'=>'form-control']); !!}
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group md-form">
											<label for="companyname" >Company Name </label>
											{!!  Form::text('companyName',null,['placeholder'=>"Company Name",'class'=>'form-control']); !!}
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-6">
										<div class="form-group md-form">
											<label for="companyurl" >Company URL </label>
											{!!  Form::text('companyUrl',null,['placeholder'=>"Company URL",'class'=>'form-control']); !!}
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group md-form">
											<label for="country" >Country <span class="star">*</span></label>
											<!-- Previously its name was regionId but it was not saving into the database so thats why name changed to country
													Ravinder Kaur 2 August, 2018 -->
											{!!	Form::select('country',$regions,null,['class'=>'form-control countryRegions']) !!}
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-12 text-right">
										<button class="btn btn-indigo btn-rounded nextBtn signUpNext" type="button">Next</button>
									</div>
								</div>
							</div>
						</div>

						<!-- Second Step -->
						<div class="row setup-content" id="step-2">
							<div class="col-md-12">
								<h3>Choose Plan</h3>
								<div class="plains_main_div">
									<p>Choose country for plans..</p>
									<input type="hidden" id="noplans" value="1"/>
								</div>
								<div class="btn-detail">
									<button class="btn btn-indigo btn-rounded prevBtn float-left" type="button">Previous</button>
									<button class="btn btn-indigo btn-rounded nextBtn float-right signUpNext" type="button">Next</button>
								</div>
							</div>
						</div>

						<!-- Third Step -->
						<div class="row setup-content" id="step-3">
							<div class="col-md-12">
								<h3>Your Payment</h3>
								<div class="payment_main_div">
								</div>
								<div class="text-right">
									<button class="btn btn-indigo btn-rounded prevBtn float-left" type="button">Previous</button>
								<button class="btn btn-indigo btn-rounded nextBtn signUpNext" type="button">Next</button>
								</div>
							</div>
						</div>
						<div class="row setup-content" id="step-4">
							<div class="col-md-12">
								<h3>Your Registration</h3>

                <div class="suceesfully-text clearfix">
                    <div class="circle-sucess-fully">
                        <img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/tick.png') }}">
                    </div>
                    <h6>Successfully Registered</h6>
                </div>
                <div class="row">
                  <div class="button-now">
										<button class="btn btn-indigo btn-rounded prevBtn" onclick="window.location.href = '{{URL('login')}}'" type="button">Login</button>
                	<!--a class="btn btn-indigo btn-rounded" href="{{URL('login')}}">Login</a-->
							</div>
            </div>
            </div>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
