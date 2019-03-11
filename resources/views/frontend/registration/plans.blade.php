
 <div class="plan-section">
	  <div class="container">
			<div class="row">
			@if(!$plansData->isEmpty())
			@foreach($plansData as $plan)


      <?php

      if ($plan->plans->billingType == 2) {
        $span = '<span class="planspan">'.$plan->plans->billing_Type->planType.'</span>';
        $Class = 'first-plan';
      } else if($plan->plans->billingType == 3) {
        $span = '<span class="planspan">'.$plan->plans->billing_Type->planType.'</span>';
        $Class = 'first-plan second-plan';
      }

       ?>
			<div class="col-md-3">
				 <div class="{{$Class}}">
				 <h5>{{$plan->plans->name}}

           <br>
           {!! $span !!}
       </h5>
				 <div class="price-dollar">
					<span class="dollar-price">{{ $plan->plan_currency->currencySymbol }} {{$plan->amount}}</span>
				 </div>
				 <div class="plan-text">
    				 <ul>
        				 <li>Valid upto <span class="text-plan"> {{$plan->plans->duration}} </span> Month</li>
        				 <li>Includes Maximum of <span class="text-plan"> {{$plan->plans->minutes_per_month}} </span> Minutes</li>
                 @if($plan->plans->billingType == 2)

                 <li>No of contacts :  {{ $plan->plans->no_of_contacts }} </li>
                 <li>Credit Amount :  {{ $plan->plan_currency->currencySymbol }} {{$plan->credit}}</li>
                 <li>To Pay :  {{ $plan->plan_currency->currencySymbol }} {{$plan->amount + $plan->credit}}</li>

                 @else
                  <li>No of contacts :  {{ $plan->plans->no_of_contacts }} </li>
                 <li>To Pay :  {{ $plan->plan_currency->currencySymbol }} {{$plan->amount}}</li>
                 @endif
    				 </ul>
						<div class="plan-btn">
					         <a href="javascript:void(0)" data-payId="{{$plan->stripe_plan_id}}" data-plainid="{{$plan->plan_id}}" class="checkPlainPrc btn btn-primary btn-block">Choose Plan</a>
				    </div>
				 </div>
			 </div>
			</div>


			@endforeach
			<input type="hidden" name="countTotalPlans" class="countTotalPlans" value="{{ count($plansData) }}"/>
			@else
			<p>No plans found..</p>
			@endif
			<input type="hidden" name="choosePlanId" class="usedPlainId"/>
			<input type="hidden" name="payId" class="payId"/>





		</div>
	  </div>
 </div>


<!-- Second Step --
<div class="plan-main-bx mid-box">
	@if(!$plansData->isEmpty())
	@foreach($plansData as $plan)
	<div class="plan-bx">
		<h3>{{$plan->plans->name}}</h3>
		<div class="plan-detail">
				<a href="javascript:void(0)" class="price">{{ $plan->plan_currency->currencySymbol }} {{$plan->amount}}</a>
				<span>Per month</span>
				<h4>{{$plan->plans->minutes_per_month}}</h4>
		</div>
		<a href="javascript:void(0)" data-payId="{{$plan->stripe_plan_id}}" data-plainid="{{$plan->plan_id}}" class="checkPlainPrc btn btn-primary btn-block">Choose Plan</a>
	</div>
	@endforeach
	<input type="hidden" name="countTotalPlans" class="countTotalPlans" value="{{ count($plansData) }}"/>
	@else
	<p>No plans found..</p>
	@endif
	<input type="hidden" name="choosePlanId" class="usedPlainId"/>
	<input type="hidden" name="payId" class="payId"/>
</div>
<!-- End Second Step -->
