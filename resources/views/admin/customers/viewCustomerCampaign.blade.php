@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-globe"></i> Customer Campaigns</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item"><a href="{{ $url }}/customers">Customers</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class=""> 
                    <div class="co-md-12">
                        @if ($message = Session::get('flash_message'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <div class="form-group text-right">
                            <a href="{{ URL(config('app.admintemplatename').'/customers') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="tile">
                    <p style="font-size:20px;"><b>Customer Name :</b> {{ $userDetail->firstName }} {{ $userDetail->lastName }}</p>
                    <div class="tile-body recordsTable">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Campaign Title</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="show_records">

                            <?php $i=0; ?>
                            @foreach($userCamp as $ucamp)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $ucamp->title }}</td>
                                    <td>{{ $ucamp->email }}</td>
                                    <td>
                                        @if(isset($ucamp->coutry_details->countryName))
                                        {{$ucamp->coutry_details->countryName}}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($ucamp->step < 4)
                                            Incomplete
                                        @else
                                            @if($ucamp->status==1)
                                                Active
                                            @else
                                                Inactive
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $url.'/campaign-detail/'.Crypt::encrypt($ucamp->id) }}"><span><i class="fa fa-eye" data-toggle="tooltip" title="View"></i> </span></a> 
                                        <a href="{{ $url.'/campaign-leads/'.Crypt::encrypt($ucamp->id) }}"><span><i class="fa fa-list" data-toggle="tooltip" title="Lead"></i> </span></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
