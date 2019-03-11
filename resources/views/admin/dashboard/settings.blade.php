@extends('layouts.admin')
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-phone"></i> Call Settings</h1>
        </div>

        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Call Settings</li>
        </ul>
    </div>
    <div class="row">
        <div id="call-setting" class="tab-pane active
            "><br>
              <div class="update-password">
                <div class="">
                @if(isset($callSetting) && !empty($callSetting))
                  {{ Form::model($callSetting,array('id'=>'call_setting_form','url'=>URL('admin/call-setting'))) }}
                @else
                  {{ Form::open(array('id'=>'call_setting_form','url'=>URL('admin/call-setting'))) }}
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
                          <label for="call_recording_display">Call Recording Display</label>
                          {{ Form::radio('call_recording_display',1)}} <span class="yes-text">Yes</span>
                          {{ Form::radio('call_recording_display',2)}} <span class="yes-text">No</span>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group call-recording">
                          <label for="call_announcement_email">Call Announcement Email</label>
                          {{ Form::radio('call_announcement_email',1,false,array('class'=>'annEmail')) }} <span class="yes-text">Yes</span>
                          {{ Form::radio('call_announcement_email',2,false,array('class'=>'annEmail')) }} <span class="yes-text">No</span>
                        </div>
                      </div>
                      <div class="col-md-12 emailDiv">
                        <div class="form-group">
                          <label for="email_subject">Email Subject</label>
                          {{ Form::text('email_subject',null,['class'=>'form-control']) }}
                        </div>
                      </div>
                      <div class="col-md-12 emailDiv">
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
    </div>
</main>
@endsection
