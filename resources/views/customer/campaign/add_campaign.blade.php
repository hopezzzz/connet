@extends('layouts.customer')
@section('content')

<style>
  .green { color: green; }
  .blue { color: #ec2235; }
</style>

<div class="content-wrapper">
   <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
         <li class="breadcrumb-item">
            <a  href = " javascript:void(0) ">Campaigns</a>
         </li>

      </ol>
      <?php
      $d = array();
         if (isset($campData['step'])) {
            for ($i=1; $i <= $campData['step']; $i++) {
              $d[$i] = $i;
            }
         }

      $api_dept_id = '';
      if(isset($campData['api_dept_id']) && trim($campData['api_dept_id']) !='' ){
        $api_dept_id = $campData['api_dept_id'];
      }
      ?>
      <!-- Icon Cards-->
      <div class="row">
         <div class="col-md-12">
            <div class="clearfix"></div>
            <!-- Grid row -->
            <div class="row d-flex justify-content-center">
               <div class="col-md-12 pl-5 pl-md-0 pb-5">
                  <!-- Stepper -->
                  <div class="steps-form-3">
                     <div class="steps-row-3 setup-panel-3">

                        <div class="steps-step-3 media contact-list-media contact-icon first-child   <?php if(isset($campData['step'] )  && $campData['step'] ==  1) {?>contact-list-media-active<?php }else{?>contact-list-media-active<?php }?> ">

                           <a href="#step-1" class="btn btn-info btn-circle-3 icons <?php if(isset($d['1'])) {?>waves-effect<?php } ?> ml-0" data-toggle="tooltip" data-placement="top" title="Set Email Address/Template"><i class="fa fa-envelope"></i></a>
                           <div class="icons-noti text-left tick">
                              @if(isset($d[1]))
                              <i class="fa fa-check-circle green valid1" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid1" style="display:none" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @else

                              <i class="fa fa-check-circle green valid1" style="display:none" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid1" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @endif
                           </div>
                           <span class="title-campaign"> Setting up the Campaign</span>
						             </div>
                        <div class="steps-step-3 media contact-list-media contact-icon <?php if(isset($campData['step'] )  && $campData['step'] ==  2) {?>contact-list-media-active<?php } ?> ">
                           <a href="#step-2" class="btn btn-info btn-circle-3 icons <?php if(isset($d['2'])) {?>waves-effect<?php } ?> ml-0" data-toggle="tooltip" data-placement="top" title="Set Call Script"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                           <div class="icons-noti text-left tick">
                              @if(isset($d[2]))
                              <i class="fa fa-check-circle green valid2" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid2" style="display:none" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @else

                              <i class="fa fa-check-circle green valid2" style="display:none" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid2" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @endif
                           </div>
                         <span class="title-campaign" style="left: 0;"> Form Mapping and Call Script</span>
						</div>
                        <div class="steps-step-3 media contact-list-media contact-icon <?php if(isset($campData['step'] )  && $campData['step'] ==  3) {?>contact-list-media-active<?php } ?> ">
                           <a href="#step-3" class="btn btn-pink btn-circle-3 icons <?php if(isset($d['3'])) {?>waves-effect<?php } ?> p-3" data-toggle="tooltip" data-placement="top" title="Set Campaign Contact"><i class="fa fa-clipboard"></i></a>
                           <div class="icons-noti text-left tick">
                              @if(isset($d[3]))
                              <i class="fa fa-check-circle green  valid3" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid3" style="display:none" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @else

                              <i class="fa fa-check-circle green valid3" style="display:none" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid3" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @endif
                           </div>
                         <span class="title-campaign"> Set campaign call settings</span>
						</div>
                        <div class="steps-step-3 media contact-list-media contact-icon  ">
                           <a href="#step-4" class="btn btn-pink btn-circle-3 icons <?php if(isset($d['4'])) {?>waves-effect<?php } ?>" data-toggle="tooltip" data-placement="top" title="Call Settings"><i class="fa fa-phone" aria-hidden="true"></i></a>
                           <div class="icons-noti text-left tick">
                              @if(isset($d[4]))
                              <i class="fa fa-check-circle green valid4" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid4" style="display:none" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @else

                              <i class="fa fa-check-circle green valid4" style="display:none" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid4" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @endif
                           </div>
                        <span class="title-campaign" style="left: 32px;"> Set campaign hours</span>
					   </div>
                        <div class="steps-step-3 media contact-list-media contact-icon last-child <?php if(isset($campData['step'] )  && $campData['step'] ==  4) {?>contact-list-media-active<?php } ?> no-height">
                           <a href="#step-5" class="btn btn-pink btn-circle-3 icons <?php if(isset($d['4'])) {?>waves-effect<?php } ?> p-3" data-toggle="tooltip" data-placement="top" title="Finish"><i class="fa fa-check" aria-hidden="true"></i></a>
                           <div class="icons-noti text-left tick">
                              @if(isset($d[4]))
                              <i class="fa fa-check-circle green valid4" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid4" style="display:none" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @else

                              <i class="fa fa-check-circle green  valid4" style="display:none" data-toggle="tooltip" title="Step Completed"></i>

                              <i class="fa fa-info-circle blue invalid4" data-toggle="tooltip" data-placement="top" title="Step incomplete."></i>

                              @endif
                           </div>
                         <span class="title-campaign" style="left: 38px;"> Confirm and Save</span>
						</div>
                     </div>
                  </div>
               </div>
               <!-- Grid column -->
               <!-- Grid column -->
               <div class="col-md-12">
                  <?php if (!empty($campData)): ?>
                  {{ Form::model($campData,array('url' => 'customer/add-campaign-steps','id' => 'add-campaign-steps','class' => 'add-campaign-steps','autocomplete' => 'off')) }}
                  <?php else: ?>
                  {{ Form::open(array('url' => 'customer/add-campaign-steps','id' => 'add-campaign-steps','class' => 'add-campaign-steps','autocomplete' => 'off')) }}
                  <?php endif; ?>
                  {!! Form::hidden('step','step1',['class'=>'campaignStep']) !!}
                  {!! Form::hidden('campId',null,['class'=>'campId']) !!}
                  <!-- First Step -->
                  <div class="row setup-content-3" id="step-1">
                     <div class="col-md-12">
                        <h3 class="font-weight-bold pl-0"><strong> Setting up the Campaign</strong></h3>
                        <div class="form-group md-form">
                           <label for="yourtitle-3" data-error="wrong" data-success="right">Campaign Title<span class="star">*</span></label>
                           {!!  Form::text('campaignsTitle',null,['class'=>'form-control validate']) !!}
                        </div>
                     </div>
                     @if( isset($campData['email']))
                     <div class="to_display col-md-12" {{ (isset($campData['email']) ) ? 'style="display:none"' : '' }}>
                     @if(trim($campData['testMail']) == '')
                     <div class="sample-email">
                        <h6 class="text-left" style="font-size: 14px;">
                           Your campaign email address is: <b><span id="campTextId">{{$campData['email']}}</span></b>

                           <i class="fa fa-clipboard" aria-hidden="true"></i>

                           <br>

                           <br>
                        </h6>
                        <h6 class="text-left" style="font-size: 14px; color:red">
Here are the instructions:<br>
1. Please add this email address as a recipient to receive lead/form submissions from this lead source. (example: If this campaign is for your website "contact us" page, then please add this email address to receive a copy of the email when a customer submits a new form).<br>
2. After step 1 has been completed, please send a test lead.<br>
3. After the testlead has been sent, please wait 60 seconds and then click the "Check Sample Email" button below.
                        </h6>

                        <button class="btn btn-mdb-color fetchEmail email-btn" type="button">Check sample email</button>
                     </div>
                     <div class="clearfix"></div>
                     <div class="mail-recived" style="padding:20px"></div>
                     @else
                     <div class="col-md-12 form-group text-left" style="font-size: 14px; color: red;">
                        INSTRUCTIONS:<br>
1. Please highlight the form responses that you would like used in your call script. You will only need to highlight the response, not the field name.<br>
2. After highlighting the response, please create a "Tag Name" for this field.<br>
3. Please ensure you highlight the customers phone number, and call this tag "Phone" - This step is important as our system uses this tag to trigger the call to the customer.<br>
4. Please ensure you highlight the call summary, and call this tag "Message" - This step is important as our system uses this tag to read the full call summary  to the customer.<br>
5. Please highlight all relevant answers, then click "next".<br>
                     </div>
                     <div style="padding:20px">
                        <div id="textDescription">
                           <label for=""><b> Mail received from {{$campData['email']}} </b></label><br>
                           {!! $campData['testMail'] !!}
                        </div>
                     </div>

                     @endif
                     @else
                      <div class="to_display col-md-12" style="display:none">
                          <div class="sample-email">
                           <h6 class="text-left" style="font-size: 14px;">
                              Your campaign email address is: <b><span id="campTextId"></span></b>
                              <i class="fa fa-clipboard" aria-hidden="true"></i>
                              <br><br>
                           </h6>
                           <h6  class="text-left" style="font-size: 14px; color: red;">
Here are the instructions:<br>
1. Please add this email address as a recipient to receive lead/form submissions from this lead source. (example: If this campaign is for your website "contact us" page, then please add this email address to receive a copy of the email when a customer submits a new form).<br>
2. After step 1 has been completed, please send a test lead.<br>
3. After the testlead has been sent, please wait 60 seconds and then click the "Check Sample Email" button below.
                           </h6>
                           <button class="btn btn-mdb-color fetchEmail email-btn" type="button">Check sample email</button>
                          </div>
                          <div class="mail-recived" style="padding:20px"></div>
                      @endif
                     @if(isset($campData['testMail']) && $campData['testMail'] !='')
                     <button class="btn btn-mdb-color add-campaign float-right" type="button">Next</button>
                     @endif
                     <button style="display:none" class="btn btn-mdb-color add-campaign add-camp-button  float-right" type="button">Next</button>
                  </div>
                   @if(!isset($campData['email']))
                    <button class="btn btn-mdb-color add-campaign toHideElement float-right" type="button">Submit</button>
                  @endif
               </div>
               <!-- Second Step -->
               <div class="row setup-content-3" id="step-2">
                  <div class="col-md-12">
                     <h3 class="font-weight-bold pl-0"><strong>Form Mapping and Call Script</strong></h3>
                     <div class="form-group md-form">
                        <h6 class="text-left" style="font-size: 14px; color: red;">INSTRUCTIONS / TIPS:<br>
1. You can use the standard script below, or please feel free to delete/edit it and create your own custom script.<br>
2. You can create a script and use the TAGS you created on the previous screen screen to insert the unique information from your lead source.<br>
3. To insert a TAG, simply use the @ symbol then begin typing the TAG name.</h6>

                        <label for="yourtemplate-3" data-error="wrong" data-success="right">Please use the field below to create the call script.</label>
                        <div class="clearfix mb-3">
                        @if(isset($campData['campaignTags']))
                           @foreach($campData['campaignTags'] as $tags)
                              <span style="cursor:pointer;" class="badge badge-pill badge-primary mr-1 tagSpan" data-val="{<?php echo $tags->tagName; ?>}">{{ $tags->tagName }}</span>
                           @endforeach
                        @endif
                        </div>
                        <textarea rows="7" class="mention form-control validate campaignTemplate" id="mentions-field" name="campaignTemplate" cols="50" aria-required="true" aria-invalid="false">@if(isset($campData['campaignTemplate'])){{ $campData['campaignTemplate'] }} @else New website enquiry received from. {insert name TAG here}.&#013;The message received is {insert message TAG here}. @endif</textarea>
                     </div>
                     <button class="btn btn-mdb-color btn-rounded prevBtn-3 float-left" type="button">Previous</button>
                     <button class="btn btn-mdb-color btn-rounded add-campaign float-right" type="button">Next</button>
                  </div>
               </div>
               <!-- Third Step -->
               <div class="row setup-content-3" id="step-3">
                  <div class="col-md-12">
                     <h3 class="font-weight-bold pl-0"><strong>Set campaign call settings</strong></h3>

                     <div class="form-group md-form clearfix">
                       <h6 class="text-left" style="font-size: 14px; color: red;">INSTRUCTIONS / TIPS: <br> Please add the Contacts to receive calls for this campaign. </h6>

                        <div class="add-input">
                           <!-- <input type="text" class="form-control" id="customerSearch"> -->
                           <input type="hidden" class="form-control" id="custId">
                           <input type="hidden" id="api_dept_id" value="{{$api_dept_id}}">
                           <button class="btn btn-mdb-color btn-rounded add_Contact add float-right openModal @if(!($campData['contactCount'] < \Auth::user()->user_plan->plans->no_of_contacts)) d-none @endif"  data-target="#add-new-tag" type="button">Add new contact </button>
                           <button class="btn btn-mdb-color btn-rounded getContacts add @if($campData['exContacts'] == 0) d-none @endif" data-url="{{URL('/customer/get-contacts')}}" type="button">Add an existing contact  </button>

                           {{-- @if(isset($campData['contactCount']) && $campData['contactCount'] > 0)
                              <button class="btn btn-mdb-color btn-rounded getContacts add @if(!($campData['campContacts'] < \Auth::user()->user_plan->plans->no_of_contacts)) d-none @endif" data-url="{{URL('/customer/get-contacts')}}" type="button">Add an existing contact  </button>
                           @endif --}}
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="table-responsive" style="padding: 20px;">
                           <table class="table table-striped table-hover contact-table">
                              <thead>
                                 <tr>

                                    <th>Name</th>
                                    <th>Phone No.</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody id="tableRowTr">
                              @if(isset($campData['campaignContact']))
                                 @forelse($campData['campaignContact'] as $campContact)
                                 <tr id="tableRow_{{$campContact->custId}}">
                                    <td data-name="name">{{$campContact->contact->name}}</td>
                                    <td data-name="contact">{{$campContact->contact->contact}}</td>
                                    <td>
                                      <a href="javascript:void(0)">
                                      <span class="removeContact" id="{{$campContact->id}}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                    </a>
                                      &nbsp;
                                      <span class="editContact" id="{{Crypt::encrypt($campContact->custId)}}">
                                      <a href="javascript:void(0)">
                                        <i class="fa fa-edit" aria-hidden="true"></i></span>

                                      </a>
                                    </td>
                                 </tr>
                                 @empty
                                 <tr>
                                    <td colspan="3">No Contact Found</td>
                                 </tr>
                                 @endforelse
                              @endif
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <div class="col-md-12 clearfix">
                        <button class="btn btn-mdb-color btn-rounded prevBtn-3 float-left" type="button">Previous</button>
                        <button class="btn btn-mdb-color btn-rounded add-campaign float-right" type="button">Next</button>
                     </div>
                  </div>
               </div>
               <!-- fourth Step -->
               <div class="row setup-content-3" id="step-4">
                  <div class="col-md-12">
                     <h3 class="font-weight-bold pl-0"><strong>Set campaign hours</strong></h3>
                     <div class="form-group md-form">
                        <label for="timezone">Country <span class="star">*</span></label>
                        {!!  Form::select('campaignCountry',$countryList,null,['class'=>'form-control']) !!}
                     </div>
                     <div class="form-group md-form">
                        <div class="weekDays-selector">
                           <label for="day" class="">Available day</label>
                           <input  id="weekday-all" class="weekday checkAllBox" type="checkbox" <?php if( isset($campData['available_days']) && (array("mon","tue","wed","thu","fri","sat",'sun') == $campData['available_days'])) echo 'checked="checked"';?> >
                           <label for="weekday-all">All</label>
                           &nbsp;&nbsp;&nbsp;
                           <input id="weekday-mon" class="weekday" type="checkbox" name="availDays[]" value="mon" <?php if( isset($campData['available_days']) && in_array('mon', $campData['available_days'])) echo 'checked="checked"';?>>
                           <label for="weekday-mon">Mon</label>
                           <input id="weekday-tue" class="weekday" type="checkbox" name="availDays[]" value="tue" <?php if( isset($campData['available_days']) && in_array('tue', $campData['available_days'])) echo 'checked="checked"';?>>
                           <label for="weekday-tue">Tue</label>
                           <input id="weekday-wed" class="weekday" type="checkbox" name="availDays[]" value="wed" <?php if( isset($campData['available_days']) && in_array('wed', $campData['available_days'])) echo 'checked="checked"';?>>
                           <label for="weekday-wed">Wed</label>
                           <input id="weekday-thu" class="weekday" type="checkbox" name="availDays[]" value="thu" <?php if( isset($campData['available_days']) && in_array('thu', $campData['available_days'])) echo 'checked="checked"';?>>
                           <label for="weekday-thu">Thu</label>
                           <input id="weekday-fri" class="weekday" type="checkbox" name="availDays[]" value="fri" <?php if( isset($campData['available_days']) && in_array('fri', $campData['available_days'])) echo 'checked="checked"';?>>
                           <label for="weekday-fri">Fri</label>
                           <input id="weekday-sat" class="weekday" type="checkbox" name="availDays[]" value="sat" <?php if( isset($campData['available_days']) && in_array('sat', $campData['available_days'])) echo 'checked="checked"';?>>
                           <label for="weekday-sat">Sat</label>
                           <input id="weekday-sun" class="weekday" type="checkbox" name="availDays[]" value="sun" <?php if( isset($campData['available_days']) && in_array('sun', $campData['available_days'])) echo 'checked="checked"';?>>
                           <label for="weekday-sun">Sun</label>
                        </div>
                     </div>
                     <div class="row">
                        <div class="form-group md-form col-md-6">
                           <label for="hour-3" data-error="wrong" data-success="right">Available Hours From <span class="star">*</span></label>
                           {!!  Form::text('availHoursFrom',null,['class'=>'form-control validate', 'id'=>'availTimeFrom']) !!}
                           <span class=""><i class="glyphicon glyphicon-time"></i></span>
                        </div>
                        <div class="form-group md-form col-md-6">
                           <label for="hour-3" data-error="wrong" data-success="right">Available Hours To <span class="star">*</span></label>
                           {!!  Form::text('availHoursTo',null,['class'=>'form-control validate','id'=>'availTimeTo']) !!}
                           <span class=""><i class="glyphicon glyphicon-time"></i></span>
                        </div>
                     </div>

                     <div class="row">
                        <div class="form-group md-form col-md-6">
                           <label for="hour-3" data-error="wrong" data-success="right">Call Delay Time (Hours:Minutes:Seconds)<span class="star">*</span></label>
                           {!!  Form::text('delayTime',isset($campData['delayTime']) ? null : '00:00:30',['class'=>'form-control validate', 'min'=>'00:00:30', 'max' => '2:00' , 'id'=>'delayTimewwww']) !!}
                           <span class=""><i class="glyphicon glyphicon-time"></i></span>
                        </div>

                     </div>



                     <div class="row clearfix d-none">
                        <div class="form-group md-form col-md-6">
                           <label for="yourhour-3" data-error="wrong" data-success="right">Break Hours From</label>
                           {!!  Form::text('breakHoursFrom',null,['class'=>'form-control validate timepicker','id'=>'breakTimeFrom']) !!}
                        </div>
                        <div class="form-group md-form col-md-6">
                           <label for="yourhour-3" data-error="wrong" data-success="right">Break Hours To</label>
                           {!!  Form::text('breakHoursTo',null,['class'=>'form-control validate timepicker','id'=>'breakTimeTo']) !!}
                        </div>

                     </div>
                     <button class="btn btn-mdb-color btn-rounded prevBtn-3 float-left" type="button">Previous</button>
                     <button class="btn btn-mdb-color btn-rounded add-campaign float-right" type="button">Next</button>
                  </div>
               </div>
               <!-- fifth Step -->
               <div class="row setup-content-3" id="step-5">
                  <div class="col-md-12">
                     <h3 class="font-weight-bold pl-0"><strong>Confirm and Save</strong></h3>
                     <!--<h2 class="text-center font-weight-bold my-4">Registration Completed !</h2>-->
                     <div>
                        <?php
                           $dayArr = array('mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday')
                           ?>
                        <div id="data">
                           <div class="col-md-12">
                              <table class="table campTable">
                                 <thead>
                                    <tr>
                                       <th style="width:15% !important" ></th>
                                       <th style="width:85%"></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td><span class="details-user"><b>Campaign Title :</b></span></td>
                                       <td><span class="details_now"> @if(isset($campData['campaignsTitle'])) {{ $campData['campaignsTitle'] }} @endif </span></td>
                                    </tr>
                                    <tr>
                                       <td><span class="details-user"><b>Campaign Template :</b></span></td>
                                       <td><span class="details_now"> @if(isset($campData['campaignTemplate'])) {{ $campData['campaignTemplate'] }} @endif </span></td>
                                    </tr>
                                    <tr>
                                       <td><span class="details-user"><b>Parse Output :</b></span></td>
                                       <td><span class="details_now"> @if(isset($campData['parserOutput'])) {{  trim(preg_replace('/\s\s+/', ' ', $campData['parserOutput']  )) }} @endif </span></td>
                                    </tr>

                                    <tr>
                                       <td><span class="details-user"><b>Country :</b></span></td>
                                       <td><span class="details_now"> @if(isset($campData['campaignCountryName'])) {{ $campData['campaignCountryName'] }} @endif </span></td>
                                    </tr>
                                    <tr>
                                       <td><span class="details-user"><b>Available Days :</b></span></td>
                                       <td><span class="details_now">  @if(isset($campData['available_days']))
                                          <?php $ds = ''; ?>
                                          @foreach($campData['available_days'] as $days)
                                          <?php $ds .= $dayArr[$days].', '; ?>
                                          @endforeach
                                          {{ rtrim($ds,', ') }} @endif </span>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><span class="details-user"><b>Call Delay Time :</b></span></td>
                                       <td><span class="details_now">  @if(isset($campData['delayTime']) && isset($campData['availHoursTo']))
                                          {{ $campData['delayTime'] }}
                                          @endif
                                       </td>
                                    </tr>
                                    <tr class="d-none">
                                       @if(isset($campData['breakHoursFrom']) && isset($campData['breakHoursTo']))
                                       <td><span class="details-user"><b>Break Hours:</b></span></td>
                                       <td><span class="details_now">{{ $campData['breakHoursFrom'] }} - {{ $campData['breakHoursTo'] }}</span></td>
                                       @endif
                                    </tr>
                                    <tr><td><b>Campaign Contact:</b></td></tr>
                                 </tbody>
                              </table>
                              <table class="table table-striped">
                                 <thead>
                                    <tr><th>Sr</th><th>Name</th><th>Phone Number</th></tr>
                                 </thead>
                                 <tbody>
                                    <?php $i=1; ?>
                                    @if(isset($campData['campaignContact']))
                                       @foreach($campData['campaignContact'] as $ct)

                                       <tr>
                                           <td>{!! $i++ !!}</td>
                                          <td>{{$ct->contact->name}}</td>
                                          <td>{{$ct->contact->contact}}</td>
                                       </tr>
                                       @endforeach
                                    @endif
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <button class="btn btn-mdb-color btn-rounded prevBtn-3 float-left" type="button">Previous</button>
                     <a class="btn btn-success btn-rounded float-right" href="{{ url('customer/campaigns')}}">Save and finish</a>
                  </div>
               </div>
               </form>
            </div>
            <!-- Grid column -->
         </div>
         <!-- Grid row -->
      </div>
   </div>
</div>
</div>
<div id="add-new-tag" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
        {{ Form::open(array('url'=>URL('customer/save-contact'),'id'=>'contact_form','autocomplete' => 'off')) }}
        <input type="hidden" name="frontreq" value="1">
        <input name="recordId" type="hidden" value="">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title">Add New Contact</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Contact Name <span class="star">*</span></label>
                        {!! Form::text('name',null,['placeholder'=>"Name",'class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Contact Email <span class="star">*</span></label>
                        {!! Form::text('email','',['placeholder'=>"Contact Email",'class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Role/Title </label>
                        {!! Form::text('role',null,['placeholder'=>"Role/Title",'class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Department/Location </label>
                        {!! Form::text('dept',null,['placeholder'=>"Department/Location",'class'=>'form-control']) !!}
                        @if(isset($recordId))
                        {!! Form::hidden('recordId',$recordId) !!}
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Country Code <span class="star">*</span></label>
                        {!! Form::select('code',$countryCode,Null,['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Phone Number <span class="star">*</span></label>
                        {!!  Form::text('number',null,['placeholder'=>"Phone Number",'class'=>'form-control']) !!}
                    </div>
                </div>
              </div>

              <?php
              $daysArr = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
              ?>
              <div class="row">
                <div class="col-md-12" style="display:none">
                  <div class="form-group">
                        <label for="">Schedule <span class="star">*</span></label>
                  </div>
                  <div class="">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Days</th>
                          <th>From</th>
                          <th>To</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        @for($i=0;$i<=6;$i++)
                        <tr>
                          <td>{{ $daysArr[$i] }}</td>
                          <td>
                            <input class="form-control validate timepicker" type="text" name="dayFrom[]" value="@if(isset($data['schedule'][$i]->from)) {{$data['schedule'][$i]->from}} @endif" id="dayF{{$i}}">
                            </td>
                          <td><input class="form-control validate timepicker" type="text" name="dayTo[]" value="@if(isset($data['schedule'][$i]->to)) {{$data['schedule'][$i]->to}} @endif" id="dayT{{$i}}"></td>

                          <td><span class="daySts" style="font-size: 30px; color: #007bff;" data-val="@if(isset($data['schedule'][$i]->status)) {{ $data['schedule'][$i]->status }} @else 0 @endif"><i class="fa @if(isset($data['schedule'][$i]->status) && $data['schedule'][$i]->status!=0) fa-toggle-on @else fa-toggle-off @endif" data-toggle="tooltip" title="" data-original-title="Change Status"></i></span><input class="sts-input" type="hidden" name="dayStatus[]" value="@if(isset($data['schedule'][$i]->status)) {{ $data['schedule'][$i]->status }} @else 0 @endif"></td>
                        </tr>
                        @endfor
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

               <div class="text-right">
                  <button type="button" class="btn btn-primary cancel-now" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success">Yes</button>
               </div>
            </div>

         </div>

      </form>
   </div>
</div>
<div id="output"></div>


<div id="campaign-contacts" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <form id="camp-contact-form" action="{{URL('/customer/add-contact-to-campaign')}}" method="post">
       <input type="hidden" class="form-control custId campId" value="{{ (isset($campData['campId']) ) ? $campData['campId'] : '' }}" name="campId">
          <input type="hidden" name="api_dept_id" class="api_dept_id_" value="{{$api_dept_id}}">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Select Contact</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">


              </div>
              </div>
              <div class="modal-footer" style="display:none">
                    <div class="text-right">
                      <div class="checkbox"><input type="checkbox" name="contactid[]" class="customerCheckbox" value=""></div>
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary" id="camp-contact">Save</button>
                    </div>
      </form>
        </div>
    </div>
</div>

<div id="add-new-uom" style="display:none" class="hide dynamic-class">
   <div class="row">
       <div class="col-sm-12">
          <div class="form-group">
             <div class="form-group">
                <input name="tagName" id="tagName" autofocus autocomplete="off" class="form-control inputRequired unitName" placeholder="Tag" type="text">
             </div>
          </div>
          <div class="form-group">
            <input type="hidden" name="indexRow" class="indexRow inputRequired">
            <input type="hidden" name="positionStart" class="positionStart inputRequired">
            <input type="hidden" name="positionEnd" class="positionEnd inputRequired">
            <input type="hidden" name="capId" class="campId inputRequired" value="{{ (isset($campData['campId']) ) ? $campData['campId'] : '' }}">
            <input type="hidden" name="label" id="label">
            <button type="button" data-ref="" class="btn btn-primary" id="createTag">Save</button>
            <button type="button" data-ref="" class="btn btn-danger" id="removeTag">Remove</button>

          </div>
       </div>
   </div>
   <div  class="clearfix"></div>
</div>
<div id="update-tag" style="display:none" class="hide dynamic-class">
   <div class="row">
       <div class="col-sm-12">
          <div class="form-group">
             <div class="form-group">
                <input name="tagName" id="tagName" autofocus autocomplete="off" class="form-control inputRequired tagName unitName" placeholder="Tag" type="text">
             </div>
          </div>
          <div class="form-group">
            <input type="hidden" name="tagRef" class="tagRef inputRequired">
            <input type="hidden" name="capId" class="campId inputRequired" value="{{ (isset($campData['campId']) ) ? $campData['campId'] : '' }}">
            <input type="hidden" name="label" id="label">
            <button type="button" data-ref="" class="btn btn-primary" id="updateTag">Save</button>
            <button type="button" data-ref="" class="btn btn-danger closePOP" id="deleteTag">Delete</button>

          </div>
       </div>
   </div>
   <div  class="clearfix"></div>
</div>

<input type="hidden" name="indexRow" class="indexRow inputRequired">
<input type="hidden" name="positionStart" class="positionStart inputRequired">
<input type="hidden" name="positionEnd" class="positionEnd inputRequired">
 <!-- <a id="popoverData" class="btn"  href = " javascript:void(0) " >Popover with data-trigger</a> -->
@if (isset($campData['step']) && $campData['step'] !=4 )
<script type="text/javascript">
  $(document).ready(function() {
    jQuery('.steps-step-3').each(function() {
      if($(this).hasClass('contact-list-media-active')){
          jQuery(this).find('a').trigger('click');
          return false;
      }
    })
  });
</script>
@else
<script type="text/javascript">
jQuery('.no-height').removeClass('contact-list-media-active');
</script>
@endif

@if(isset($campData['campaigns_tags']) && count($campData['campaigns_tags']) > 0)
<script type="text/javascript">
function hightLight (element, start, end,tagName , id) {
    var str = element[0].innerHTML;
    str = `${ str.substr(0, start) }<mark data-name="${tagName.replace("\"", "")}" class="campaignTag" data-id="${id}">${str.substr(start, end - start)}</mark>${str.substr(end)}`;
    element[0].innerHTML = str.replace("\"", "");

}
<?php

foreach ($campData['campaigns_tags'] as $key => $value) { ?>
  jQuery('.testMail').each(function(index) {
    var thiss = $(this);
    if (index == '<?php echo $value->indexRow?>') {
      hightLight(thiss, '<?php echo $value->positionStart?>', '<?php echo $value->positionEnd?>','<?php echo $value->tagName?>','<?php echo $value->id?>')
    }
  })
<?php } ?>

function closePOP() {
  $('.campaignTag').popover('dispose');
}


</script>
@endif
@endsection
