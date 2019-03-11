@extends('layouts.customer')
@section('content')

<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumbs-->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a  href = " javascript:void(0) ">Contacts</a>
      </li>

    </ol>

    <!-- Icon Cards-->
    <div class="row">
      <div class="col-md-12">
        <div style="margin: 10px auto;padding: 0;" class="col-md-4 pull-right text-right clearfix">
          <a class="btn btn-primary" href="{{URL('customer/contacts')}}">Back</a>
        </div>
      </div>
      <div class="col-md-12">
          @if(isset($data))
            {{ Form::model($data,array('url'=>URL('customer/save-contact'),'id'=>'contact_form','autocomplete' => 'off')) }}
          @else
            {{ Form::open(array('url'=>URL('customer/save-contact'),'id'=>'contact_form','autocomplete' => 'off')) }}
          @endif
          <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Contact Name <span class="star">*</span></label>
                    {!! Form::text('name',null,['placeholder'=>"Name",'class'=>'form-control']) !!}
                    @if(isset($recordId))
                    {!! Form::hidden('recordId',$recordId) !!}
                    @else
                    {!! Form::hidden('recordId',null) !!}
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Contact Email <span class="star">*</span></label>
                    {!! Form::text('email',null,['placeholder'=>"Contact Email",'class'=>'form-control']) !!}
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
          // echo '<pre>';
          // print_r($data['schedule']);
          ?>
          <div class="row" style="display: none;">
            <div class="col-md-12">
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
          <div class="row">
            <div class="col-md-12 text-right">
              @if(isset($recordId))
                {!!  Form::submit('Update',['class'=>'btn btn-primary']) !!}
              @else
                {!!  Form::submit('Submit',['class'=>'btn btn-primary']) !!}
              @endif

            </div>
          </div>
          {{ Form::close() }}
        </div>
    </div>
  </div>
</div>
@endsection
