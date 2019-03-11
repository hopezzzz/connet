@extends('layouts.app')
@section('content')

 

<div class="">
	<div class="main-wrapper">
		<div class="choose-sec">
			<div class="card">
				<div class="card-body mb-4">
					<!-- Stepper -->
					<div class="steps-form">
						<div class="steps-row setup-panel" <?php  ?>>
							<div class="steps-step">
								<a href="#step-1" type="button" class="btn btn-indigo btn-circle"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/your-detail-h.png') }}"/></a>
								<p>Fill Details</p>
							</div>
							<div class="steps-step">
								<a href="#step-2" type="button" class="btn btn-default btn-circle"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/plan-h.png') }}"/></a>
								<p> Choose Plan</p>
							</div>
							<div class="steps-step">
								<a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/payment-h.png') }}"/></a>
								<p> Your Payment</p>
							</div>
							<div class="steps-step">
								<a href="#step-4" id="register_confirm" type="button" class="btn btn-default btn-circle" disabled="disabled"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/register-h.png') }}"/></a>
								<p>Your Registration</p>
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

				</div>
			</div>
		</div>
	</div>
</div>

@endsection
