<style media="screen">

</style>
<div class="row">
  <div class="col-md-4">
      <h3>Client Information</h3>
      <div class="row">
        <div class="col-md-4">
          <label for="">Name</label>
        </div>
        <div class="col-md-8">
          <span>
            @if(isset($payMentData->users->firstName))
              {{$payMentData->users->firstName}}
            @else
              N/A
            @endif</span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="">Email</label>
        </div>
        <div class="col-md-8">
          <span>
            @if(isset($payMentData->users->email))
              {{$payMentData->users->email}}
            @else
              N/A
            @endif</span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="">Phone No</label>
        </div>
        <div class="col-md-8">
          <span>
            @if(isset($payMentData->users->phoneNo))
              {{$payMentData->users->phoneNo}}
            @else
              N/A
            @endif</span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <label for="">Company Name</label>
        </div>
        <div class="col-md-8">
          <span>
            @if(isset($payMentData->users->companyName))
              {{$payMentData->users->companyName}}
            @else
              N/A
            @endif</span>
        </div>
      </div>
  </div>
  <div class="col-md-4">
    <h3>Plan Information</h3>
    <div class="row">
      <div class="col-md-4">
        <label for="">Plan Name</label>
      </div>
      <div class="col-md-8">
        <span>
          @if(isset($payMentData->plans->name))
            {{$payMentData->plans->name}}
          @else
            N/A
          @endif
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <label for="">Amount</label>
      </div>
      <div class="col-md-8">
        <span>
          @if(isset($payMentData->plans->plan_prices[0]->amount))
            {{$payMentData->plans->plan_prices[0]->plan_currency->currencySymbol}} {{$payMentData->plans->plan_prices[0]->amount}}
          @else
            N/A
          @endif
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <label for="">Minutes/Month</label>
      </div>
      <div class="col-md-8">
        <span>
          @if(isset($payMentData->plans->minutes_per_month))
            {{$payMentData->plans->minutes_per_month}}
          @else
            N/A
          @endif
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <label for="">Plan Duration</label>
      </div>
      <div class="col-md-8">
        <span>
          @if(isset($payMentData->plans->duration))
            {{$payMentData->plans->duration}} Month
          @else
            N/A
          @endif
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <label for="">Description</label>
      </div>
      <div class="col-md-8">
        <span>
          @if(isset($payMentData->plans->description))
            {{$payMentData->plans->description}}
          @else
            N/A
          @endif
          </span>
      </div>
    </div>
  </div>
  <div class="col-md-4">
   <h3>Payment Details</h3>
    <div class="row">
            <div class="col-md-12">
          <div class="form-group">
            <label for="cardHolderName" class="control-label">Card Holder Name <span class="star">*</span></label>
            <input id="cardHolderName" type="tel" class="input-lg form-control" name="cardHolderName" placeholder="Card Holder Name" required="">
          </div>
        </div>
		</div>
  <div class="row">
            <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Card Number <span class="star">*</span></label>
            <input id="cardNumber" type="tel" class="input-lg form-control cc-number mastercard identified" name="cardNumber" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" required="">
          </div>
        </div>
		</div>
    <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="ccExpiryMonth" class="control-label">Card Expiry <span class="star">*</span></label>
            <input id="ccExpiryMonth" type="tel" class="input-lg form-control cc-exp" name="ccExpiryMonth" autocomplete="cc-exp" placeholder="•• / ••" required="">
          </div>

        </div>
		</div>
<div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Card CVC <span class="star">*</span></label>
            <input id="cvvNumber" type="tel" class="input-lg form-control cc-cvc" name="cvvNumber" autocomplete="off" placeholder="•••" required="">
          </div>
        </div>
  </div>
  </div>

  <div class="clearfix">

  </div>
    <hr>
      <br>



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
<input type="hidden" name="planAmount" class="planAmount" value="{{$payMentData->plans->plan_prices[0]->amount}}">
<input type="hidden" name="planName" class="planName" value="{{$payMentData->plans->name}}">
