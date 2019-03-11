@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-envelope"></i> Email Templates</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="{{ URL(config('app.admintemplatename').'/email-templates') }}">Email Templates</a></li>
                @if(isset($data['record_id']) and $data['record_id']!="")
                    <li class="breadcrumb-item"><a  href = " javascript:void(0) ">Update</a></li>
                @else
                    <li class="breadcrumb-item"><a  href = " javascript:void(0) ">Add New</a></li>
                @endif
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if(isset($data['record_id']) and $data['record_id']!="")
                    <h3>Update Template</h3>
                @else
                    <h3>Add New Template</h3>
                @endif
                <div class="tile">
                {{ Form::model($data,array('url' => URL('admin/save-template'),'id' => 'template-form','class' => 'template-form','autocomplete' => 'off')) }}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Template Title<span class="star">*</span></label>
                            {!!  Form::text('name',null,['placeholder'=>"Template Title",'class'=>'form-control']) !!}
                            {!!  Form::hidden('record_id',$data['record_id']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Template Content</label>
                            {!!  Form::textarea('content',null,['placeholder'=>"Template Content",'class'=>'form-control','rows'=>'10']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        {{ Form::submit('Submit',['class'=>'btn btn-primary submitBtn']) }}
                    </div>
                
                </div>
                {{ Form::close() }}
            </div>
		</div>
        </div>
    </main>
@endsection
