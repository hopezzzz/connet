@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-list"></i> Plans</h1>
            </div>

            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="{{ URL(config('app.admintemplatename').'/plans') }}">Plans</a></li>
                @if(isset($planData['record_id']) and $planData['record_id']!="")
                    <li class="breadcrumb-item"><a  href = " javascript:void(0) ">Update Plan</a></li>
                @else
                    <li class="breadcrumb-item"><a  href = " javascript:void(0) ">Add New Plan</a></li>
                @endif
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if(isset($planData['record_id']) and $planData['record_id']!="")
                    <h3>Update Plan</h3>
                @else
                    <h3>Add New Plan</h3>
                @endif
                <div class="tile">
                {{ Form::model($planData['plan_detail'],array('url' => $planData['form_action'],'id' => 'plan-form','class' => 'plan-form','autocomplete' => 'off')) }}
                <div class="row">
                    <div class="col-md-4 plans">
                        <div class="form-group">
                            <label for="">Plan Name<span class="star">*</span></label>
                            {!!  Form::text('name',null,['placeholder'=>"Plan Name",'class'=>'form-control']) !!}
                            {!!  Form::hidden('record_id',$planData['record_id']) !!}
                        </div>
                    </div>
                    <div class="col-md-4 plans">
                        <div class="form-group">
                            <label for="">Plan Type<span class="star">*</span></label>
                            {!! Form::select('billingType',$planData['billingType'],null, ['class'=>'form-control billingType']) !!}
                        </div>
                    </div>
                    <div class="col-md-4 plans">
                        <div class="form-group">
                            <label for="">No of contacts<span class="star">*</span></label>
                            {!!  Form::text('no_of_contacts',null,['placeholder'=>"No of contacts",'class'=>'form-control parseInt']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Description</label>
                        {!!  Form::textarea('description',null,['placeholder'=>"Description",'class'=>'form-control','rows'=>'4']) !!}
                    </div>
                </div>
            </div>
                <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">No. of minutes/month <span class="star">*</span></label>
                        {!!  Form::text('minutes_per_month',null,['placeholder'=>"No of Minutes/Month",'class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Estimated number of leads/month <span class="star">*</span></label>
                        {!!  Form::text('leads_per_month',null,['placeholder'=>"Estimated No of Leads/Month",'class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Duration</label>
                        <select class="form-control" name="duration">
                            <option value="">Select Duration</option>
                            <?php for($i=1;$i<=12;$i++){ ?>
                            <option value="{{ $i }}" @if(isset($planData['plan_detail']->duration)) @if($planData['plan_detail']->duration==$i) selected @endif @endif>{{ $i }} month</option>
                            <?php }?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12"><h5>Add Prices <span class="star">*</span></h5></div>
            </div>
            @if(isset($planData['record_id']) and $planData['record_id']!="")
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-responsive priceTable">
                        <tr><td>Amount</td><td class="creditfield">Credit Amount</td><td class="creditfield">Cost Per Minute</td><td>Currency</td></tr>
                    @foreach($planData['plan_detail']->plan_prices as $key=>$price)
                        {{Form::hidden('priceID[]',$price->id)}}
                          {!!  Form::hidden('plan_id['.$key.']',$price->stripe_plan_id) !!}
                            <tr>
                                <td class="form-group">
                                    {!!  Form::text('price['.$key.']',$price->amount,['placeholder'=>"Price",'class'=>'form-control priceForm']) !!}
                                </td>
                                <td class="form-group creditfield" style="display:none">
                                    {!!  Form::text('credit['.$key.']',$price->credit,['placeholder'=>"Credit",'class'=>'form-control creditForm']) !!}
                                </td>
                                <td class="form-group creditfield" style="display:none">
                                    {!!  Form::text('per_min_cost['.$key.']',$price->per_min_cost,['placeholder'=>"Cost Per Minute",'class'=>'form-control perMinCostForm']) !!}
                                </td>
                                <td class="form-group">
                                    {!! Form::select('currency_id['.$key.']',$planData['currency'],$price->plan_currency->countryId, ['class'=>'form-control currency_id']) !!}
                                </td>
                                <td>
                                    <span id="{{ $price->id }}" class="delete-price"><i class="fa fa-close"></i></span>
                                </td>
                            </tr>
                    @endforeach
                    </table>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-12 ">
                    <table class="table table-responsive priceTable">
                        <tr><td>Amount</td><td class="creditfield">Credit Amount</td><td class="creditfield">Cost Per Minute</td><td>Currency</td></tr>
                        <tr>
                            <td class="form-group">
                                {!!  Form::text('price[0]',null,['placeholder'=>"Price",'class'=>'form-control priceForm parseInt']) !!}
                            </td>
                            <td class="form-group creditfield" style="display:none">
                                {!!  Form::text('credit[0]',null,['placeholder'=>"Credit",'class'=>'form-control creditForm']) !!}
                            </td>
                            <td class="form-group creditfield" style="display:none">
                                {!!  Form::text('per_min_cost[0]',null, ['placeholder'=>"Cost Per Minute",'class'=>'form-control perMinCostForm']) !!}
                            </td>
                            <td class="form-group">
                                {!! Form::select('currency_id[0]',$planData['currency'],null, ['class'=>'form-control currency_id']) !!}
                            </td>
                            <td>
                                <span class="delete-price"><i class="fa fa-close"></i></span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-6 text-left">
                    <span class="add-more-price">Add More <i class="fa fa-plus-square"></i></span>
                </div>
            </div>
            {{Form::hidden('deletePrice',null,['class'=>"delPrice"])}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Features</label>
                        {!!  Form::textarea('features',null,['placeholder'=>"Features",'class'=>'form-control','rows'=>'4']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    @if(isset($planData['record_id']) and $planData['record_id']!="")
                        <button type="submit" class="btn btn-primary submitBtn">Update</button>
                    @else
                        <button type="submit" class="btn btn-primary submitBtn">Save</button>
                    @endif
                </div>
                {{ Form::close() }}
            </div>
        </div>
		</div>
        </div>
    </main>
@endsection
