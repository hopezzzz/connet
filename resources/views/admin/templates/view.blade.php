@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-envelope"></i> Email Templates</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a  href = " javascript:void(0) ">Email Templates</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        @if ($message = Session::get('flash_message'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <div class="form-group text-right">
                            <a href="{{ URL(config('app.admintemplatename').'/email-templates') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="tile">
                    <h2>{{$data->title}}</h2>
                    <p>{!! $data->content !!}</p>					
                </div>
            </div>
        </div>
    </main>
@endsection
