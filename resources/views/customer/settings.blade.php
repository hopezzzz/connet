@extends('layouts.customer')
@section('content')
<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a  href = " javascript:void(0) ">Settings</a>
      </li>
    </ol>
    <!-- Icon Cards-->
    <div class="row">
      @php
      $currency = Auth::user()->region->currency;
      $user = \App\User::with(['user_plan.plans.plan_prices'=>function($q) use($currency) {
        $q->where('currency_id',$currency);
      }])->where('id',Auth::user()->id)->first();
      $currencySymbol = $user->user_plan->plans->plan_prices[0]->plan_currency->currencySymbol;
      @endphp
      <div class="col-md-12">
        <div class="setting-tabs">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#call-setting">Call Settings</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#credit-recharge">Credit Recharge</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#password-setting">Change  Password</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#cards-setting">Cards</a>
            </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div id="password-setting" class="tab-pane"><br>
              <div class="update-password">
                {{ Form::open(array('url'=>URL('customer/change-password'), 'id'=>'change_password')) }}
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="pass">Enter Old Password <span class="star">*</span></label>
                      {!! Form::password('old_pass',['class'=>'form-control']) !!}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="pass">Enter New Password <span class="star">*</span></label>
                      {!! Form::password('new_pass',['class'=>'form-control']) !!}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="pass">Enter Confirm Password <span class="star">*</span></label>
                      {!! Form::password('new_pass2',['class'=>'form-control']) !!}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    {!! Form::submit('Save',['class'=>'btn btn-primary']) !!}
                  </div>
                </div>
                {{ Form::close() }}
              </div>
            </div>
            <div id="cards-setting" class="tab-pane"><br>
              <div class="update-password">
                <div class="col-md-12">
                {{ Form::open(array('url'=>URL('customer/save-card'), 'id'=>'save-card')) }}
                <?php $card = false; ?>
                <div class="formArea">
                  @if(!empty($output['data']))
                  <?php foreach ($output['data'] as $key => $value){ ?>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cc-number" class="control-label">Card Holder Name  <span class="star">*</span></label>
                        <input type="text" class="input-lg form-control" name="cardHolderName" value="{{$value['name']}}" placeholder="Card Holder Name" required="">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cc-number" class="control-label">Card number  <span class="star">*</span></label>
                        <input id="cc-number" type="tel" class="input-lg form-control cc-number mastercard identified" name="cardNumber" value="{{'XXXX XXXX XXXX ' .$value['last4']}}" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" readonly="">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cc-exp" class="control-label">Card expiry  <span class="star">*</span></label>
                        <input id="cc-exp" type="tel" class="input-lg form-control cc-exp" name="ccExpiryMonth" autocomplete="cc-exp" value="{{$value['exp_month'].'/'.$value['exp_year']  }}" placeholder="•• / ••" readonly="">
                      </div>
                    </div>
                    {!! Form::hidden('card',$value['id']) !!}

                  <?php $card=true; }; ?>

                  @else

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="newCardHolderName" class="control-label">Card Holder Name  <span class="star">*</span></label>
                      <input type="text" class="input-lg form-control" id="newCardHolderName" name="newCardHolderName" placeholder="Card Holder Name" required="">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="cc-number" class="control-label">Card number  <span class="star">*</span></label>
                      <input id="cc-number" type="tel" class="input-lg form-control cc-number mastercard identified" name="newcardNumber" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" required="">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="cc-exp" class="control-label">Card expiry  <span class="star">*</span></label>
                      <input id="cc-exp" type="tel" class="input-lg form-control cc-exp" name="newccExpiryMonth" autocomplete="cc-exp" placeholder="•• / ••" required="">
                    </div>

                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="cc-cvc" class="control-label">Card CVC  <span class="star">*</span></label>
                      <input id="cc-cvc" type="tel" class="input-lg form-control cc-cvc" name="newcvvNumber" autocomplete="off" placeholder="•••" required="">
                    </div>
                  </div>

                  @endif
                </div>
                    <div class="col-md-4">
                        <div class="btn-main">
                            <a <?php if(!$card) {?> style="display:none" <?php }?> href="javascript:void(0)" class="btn btn-danger deleteRecord">Delete</a>
                            <div class="pull-right">
                            {!! Form::submit('Save',['class'=>'btn btn-primary']) !!}
                        </div>
                      </div>
                    </div>
                  </div>
                  {{ Form::close() }}
              </div>
            </div>
            <!-- Call Setting Tab -->
            <div id="call-setting" class="tab-pane active
            "><br>
              <div class="update-password">
                <div class="">
                @if(isset($callSetting) && !empty($callSetting))
                  {{ Form::model($callSetting,array('id'=>'call_setting_form','url'=>URL('customer/call-setting'))) }}
                @else
                  {{ Form::open(array('id'=>'call_setting_form','url'=>URL('customer/call-setting'))) }}
                @endif
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="call-transfer-hours">Call Transfer Hours</label>
                          {{ Form::textarea('call_transfer_hours',old('message'),['class'=>'form-control','cols'=>10,'rows'=>2])}}
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group call-recording">
                          <label for="call_recording_display">Call Recording Display</label>
                          {{ Form::radio('call_recording_display',1,false,array('class'=>'recDisp'))}} <span class="yes-text">Yes</span>
                          {{ Form::radio('call_recording_display',2,false,array('class'=>'recDisp'))}} <span class="yes-text">No</span>
                        </div>
                      </div>
                      <div class="col-md-12 callAnn @if($callSetting['call_recording_display']!=1) d-none @endif">
                        <div class="form-group">
                          <label for="call_annoucement">Call Announcement</label>
                          {{ Form::textarea('call_annoucement',old('message'),['class'=>'form-control','cols'=>10,'rows'=>2])}}
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="welcome_message">Welcome Message</label>
                          {{ Form::textarea('welcome_message',old('message'),['class'=>'form-control','cols'=>10,'rows'=>2])}}
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="customer_wait_message">Customer Wait Message</label>
                          {{ Form::textarea('customer_wait_message',old('message'),['class'=>'form-control','cols'=>10,'rows'=>2])}}
                        </div>
                      </div>
                      
                      <div class="col-md-12">
                        <div class="form-group call-recording">
                          <label for="call_announcement_email">Call Announcement Email</label>
                          {{ Form::radio('call_announcement_email',1,false,array('class'=>'annEmail')) }} <span class="yes-text">Yes</span>
                          {{ Form::radio('call_announcement_email',2,false,array('class'=>'annEmail')) }} <span class="yes-text">No</span>
                        </div>
                      </div>
                      <div class="col-md-12 emailDiv @if($callSetting['call_announcement_email']!=1) d-none @endif">
                        <div class="form-group">
                          <label for="email_subject">Email Subject</label>
                          {{ Form::text('email_subject',null,['class'=>'form-control']) }}
                        </div>
                      </div>
                      <div class="col-md-12 emailDiv @if($callSetting['call_announcement_email']!=1) d-none @endif">
                        <div class="form-group">
                          <label for="email_body">Email Body</label>
                          {{ Form::textarea('email_body',old('message'),['class'=>'form-control','cols'=>10,'rows'=>2])}}
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="retry_time">Retry Time</label>
                          {{ Form::text('retry_time',null,['class'=>'form-control']) }}
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="retry_delay_second">Retry Delay Time (in seconds)</label>
                          {{ Form::text('retry_delay_second',null,['class'=>'form-control']) }}
                        </div>
                      </div>
                      <div class="col-md-12">
                        <input type="submit" class="btn btn-primary" value="Submit">
                      </div>
                    </div>
                  </div>
                {{ Form::close() }}
                </div>
              </div>
            </div>
            <!-- Credit Recharge Tab -->
            <div id="credit-recharge" class="tab-pane"><br>
              @php
              $rechargeArr = array(
                '0'=>'Select Amount',
                '25'=>25,
                '50'=>50,
                '100'=>100,
                '150'=>150,
                '200'=>200,
                '300'=>300,
                '400'=>400,
                '500'=>500,
                '1000'=>1000,
                '2000'=>2000
              );
              foreach($rechargeArr as $k => $v){
                if($k!=0){
                  $rArr[$k] = $currencySymbol.' '.$v;
                }else{
                  $rArr[$k] = $v;
                }
              }
              @endphp
              <div class="update-password">
                {{ Form::open(array('url'=>URL('recharge-credit'), 'method'=>'POST', 'id'=>'recharge_form')) }}
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="recharge Amount">Select Recharge Amount</label>
                    {{ Form::select('rechargeAmt',$rArr,null, ['class'=>'form-control']) }}
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    {{ Form::submit('Recharge',['class'=>'btn btn-primary']) }}
                  </div>
                </div>
                {{ Form::close() }}  
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirm_delete" data-id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Delete Card</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
              <h6>As you cannot recover it, Are you sure you want to delete this card ?.</h6>
              <div class="text-right">
                  <button type="button" class="btn btn-primary cancel-now" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-primary deleteCard">Yes</button>
              </div>
          </div>
      </div>
  </div>
</div>
<script src="{{ asset('assets/'.config("app.frontendtemplatename").'/js/jquery.payment.js') }}"></script>

<script type="text/javascript">
jQuery(function($) {
  $(document).find('[data-numeric]').payment('restrictNumeric');
  $(document).find('.cc-number').payment('formatCardNumber');
  $(document).find('.cc-exp').payment('formatCardExpiry');
  $(document).find('.cc-cvc').payment('formatCardCVC');
  $.fn.toggleInputError = function(erred) {
    this.parent('.form-group').toggleClass('has-error', erred);
    return this;
  };


});
</script>
@endsection
