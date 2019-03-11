@extends('layouts.customer') 
@section('content')
<style type="text/css">
   .green{
      color: green;
   }
</style>
<div class="content-wrapper">
<div class="container-fluid">
<!-- Breadcrumbs-->
<ol class="breadcrumb">
   <li class="breadcrumb-item">
      <a href=" javascript:void(0) ">Plans & Billing</a>
   </li>
</ol>
@php $sub_id = '' @endphp
<!-- Icon Cards-->
<div class="row">
   <div class="col-md-12">
      <ul class="nav nav-tabs">
         <li class="nav-item">
            <a class="leadTabs nav-link  active " data-toggle="tab" href="#existing_plan">Existing Plan</a>
         </li>
         @if(isset($userPlan) && $userPlan!='') 
         <li class="nav-item">
            <a class="leadTabs nav-link " data-toggle="tab" href="#new_plan">Upgrade & Degrade Plans</a>
         </li>
         @endif
      </ul>
      <div class="tab-content">
         <div id="existing_plan" class="tab-pane  active ">
            <div class="table-responsive recordsTable">
               <div class="table table-striped table-hover">
                  <table class="table">
                     <thead>
                        <tr>
                           <th scope="col">Sno.</th>
                           <th scope="col">Plan Name</th>
                           <th scope="col">Date</th>
                           <th scope="col">Valid upto</th>
                           <th scope="col">No. of Minutes/Month</th>
                           <th scope="col">Leads per month</th>
                           <th scope="col">Amount</th>
                           <th scope="col">info</th>
                           <th scope="col">Status</th>
                           <th scope="col">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $i=0; ?>
                        <?php $i++; ?>
                        @if(isset($userPlan) && $userPlan!='')
                           @php $sub_id = $userPlan->sub_id @endphp
                           <tr id="tr{{$userPlan->sub_id}}">
                              <td>{{$i}}</td>
                              <td>{{ $userPlan->usersPlans->name }}</td>
                              <td>{{ gmdate("m-d-Y", ($userPlan->startDate))}}</td>
                              <td class="valid_up_to" data-ref="{{ gmdate('m-d-Y', ($userPlan->endDate)) }}">{{ gmdate("m-d-Y", ($userPlan->endDate)) }}</td>
                              <td>{{ $userPlan->usersPlans->minutes_per_month }}</td>
                              <td>{{ $userPlan->usersPlans->leads_per_month }}</td>
                              <td>
                                 <span class="text-right"></span> {{ $userPlan->users->region->countries->currencySymbol }} {{$userPlan->amount_paid}}
                              </td>
                              @php $canceled_at = 'Active' @endphp
                              @if($userPlan->canceled_at == 1 )
                              @php $canceled_at = 'Subscrition cancelled at '.gmdate('m-d-Y', ($userPlan->cancelDate)) @endphp
                              @endif
                              @if($userPlan->canceled_at == 2 )
                              @php $canceled_at = 'Subscription will be cancelled at the end of current period on '.gmdate('m-d-Y', ($userPlan->cancelDate)) @endphp
                              @endif
                              <td><i class="fa fa-info-circle green" data-toggle="tooltip" title="{{$canceled_at}}"></i></td>
                              <td> <span class="badge badge-secondary text-uppercase">{{ $userPlan->status}}</span></td>
                              @if(isset($userPlan->endDate))
                              @if( gmdate('Y-m-d', ($userPlan->endDate)) >= date('Y-m-d') )
                              <td>
                                 @if( strtolower($userPlan->status) != 'canceled' )
                                 <a class="unsubscribePlan" href="javascript:void(0)" data-ref="{{$userPlan->sub_id}}"> <img src="{{ asset('assets/btn_unsubscribe_LG.gif')}}"> </a>
                                 @else
                                 <span class="badge badge-secondary text-uppercase">{{ $userPlan->status}}</span> @endif
                              </td>
                              @else
                              <td> <span class="badge badge-secondary text-uppercase">{{ $userPlan->status}}</span> </td>
                              @endif
                              @endif
                           </tr>
                        @else
                           <tr><td colspan="10">Unable to fetch payment information from Stripe</td></tr>
                        @endif
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         @if(isset($userPlan) && $userPlan!='') 
         <!-- Leads Summary -->
         <div id="new_plan" class="tab-pane ">
            <div class="plains_main_div">
               <div class="plan-section">
                  {{ Form::open(array('url' => url('/sufflePlan'),'id' => 'sufflePlan-form','class' => 'plan-form','autocomplete' => 'off')) }}
                  <div class="container">
                     <div class="row">
                        @php
                        $choosePlanId = '';
                        $planplan_id = '';
                        @endphp
                        @if(!$plansData->isEmpty()) @foreach($plansData as $plan)
                        <?php
                           if ($plan->plans->billingType == 2) {
                             $span = '<span class="planspan">Pay Per Minute</span>';
                             $Class = 'first-plan';
                           } else if($plan->plans->billingType == 3) {
                             $span = '<span class="planspan">Trail Plan</span>';
                             $Class = 'first-plan second-plan';
                           }
                           
                            $btnSelect = 'Choose Plan';
                           $acClass = '';
                           if($plan->stripe_plan_id == $userPlan->plan_id && $userPlan->status == 'Active'){
                              $choosePlanId = $plan->stripe_plan_id;
                              $btnSelect = 'Selected';
                              $acClass = 'activeCls';
                              $planplan_id = $plan->plan_id;
                              $vClass = true;
                           }
                           $noc = $plan->plans->no_of_contacts;
                           $contactNow = \Auth::user()->user_plan->plans->no_of_contacts;
                           $userContact = \Auth::user()->user_contacts->count();
                           $check = 0;
                           if ($userContact > 0 ) {
                             if( $noc < $contactNow){
                               if ($userContact > $noc) {
                                 $check = 1;
                               }
                             }else{
                               $check = 0;
                             }
                           }
                           
                           
                           ?>
                        <div class="col-md-3">
                           <div class="{{$Class}}">
                              <h5>{{$plan->plans->name}}<br>{!! $span !!}</h5>
                              <div class="price-dollar" id="{{ $plan->plan_currency->currencySymbol }} {{$plan->amount}}">
                                 <span class="dollar-price">{{ $plan->plan_currency->currencySymbol }} {{$plan->amount}}</span>
                              </div>
                              <div class="plan-text">
                                 <ul>
                                    <li>Valid upto <span class="text-plan"> {{$plan->plans->duration}} </span> Month</li>
                                    <li>Includes Maximum of <span class="text-plan"> {{$plan->plans->minutes_per_month}} </span> Minutes</li>
                                    @if($plan->plans->billingType == 2)
                                    <li>No of contacts : {{ $plan->plans->no_of_contacts }} </li>
                                    <!-- <li>Credit Amount : {{ $plan->plan_currency->currencySymbol }} {{$plan->credit}}</li> -->
                                    <li>To Pay : {{ $plan->plan_currency->currencySymbol }} {{$plan->amount}}</li>
                                    @else
                                    <li>To Pay : {{ $plan->plan_currency->currencySymbol }} {{$plan->amount}}</li>
                                    @endif
                                 </ul>
                                 <div class="plan-btn">
                                    <a href="javascript:void(0)" data-payId="{{$plan->stripe_plan_id}}" data-plainid="{{$plan->plan_id}}" data-mod="{{$check}}" class="checkPlainPrc btn btn-primary btn-block {{$acClass}}">{{$btnSelect}}</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endforeach
                        <input type="hidden" name="countTotalPlans" class="countTotalPlans" value="{{ count($plansData) }}" /> @else
                        <p>No plans found..</p>
                        @endif
                        <input id="modelData" type="hidden" name="modelData" class="modelToken" value="0">
                        <input type="hidden" name="choosePlanId" class="usedPlainId" value="{{trim($planplan_id)}}">
                        <input type="hidden" name="payId" class="payId" value="{{trim($choosePlanId)}}"/>
                        <input type="hidden" id="sub_id" name="sub_id" value="{{trim($sub_id)}}">
                     </div>
                     <br>
                     <button class="btn pull-right btn-indigo btn-rounded nextBtn sufflePlan subPlanData" type="button">Save Charges</button>
                     <!-- <input type="submit" name="" value="Save Charges"> -->
                     {{ Form::close() }}
                  </div>
               </div>
            </div>
         </div>
         @endif
      </div>
   </div>
</div>
<!-- The Modal -->
<div class="modal" id="planDegrade">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Important Instructions</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            {{ Form::open(array('url'=>'customer/remove-contacts', 'id'=>'downgradeContacts')) }}
            <input type="hidden" name="planId" class="usedPlainId" value="">
            <h6 style="font-size: 14px"> When downgrading an account you are now able to have only 5 contact numbers. This may cause some of your existing campaigns to be PAUSED if you haven't already removed the excess phone numbers.
               <br>Please visit the "Campaigns" tab and edit the campaigns shown in RED (if any)
            </h6>
            <div class="campaigns-modal-table">
               <div class="table-responsive">
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th>Sr.</th>
                           <th>Contact Name</th>
                           <th>Contact Number</th>
                           <th>Email</th>
                           <th>Select</th>
                        </tr>
                     </thead>
                     <tbody>
                        @php $i=0; @endphp
                        @forelse(\Auth::user()->user_contacts as $contact)
                        @php $i++; @endphp
                        <tr @if(in_array($contact->id, $campContact)) class="green" @endif>
                           <td>{{ $i }}</td>
                           <td>{{ $contact->name }}</td>
                           <td>{{ $contact->contact }}</td>
                           <td>{{ $contact->email }}</td>
                           <td><input type="checkbox" name="removeContact[]" value="{{ $contact->id }}" autocomplete="off"><span class="glyphicon glyphicon-ok"></span></td>
                        </tr>
                        @empty
                        <tr>
                           <td colspan="5">No record found !</td>
                        </tr>
                        @endforelse
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
         </div>
         {{ Form::close() }}
      </div>
   </div>
</div>

@endsection