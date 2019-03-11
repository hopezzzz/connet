@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-globe"></i> Customers</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Customers</li>
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
                            <a href="{{ URL(config('app.admintemplatename').'/customers') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="tile row">
                    <div class="col-md-6">
                        <h2>{{ ucfirst($userData->firstName) }} {{ ucfirst($userData->lastName)  }}</h2>
                          <div class="">
                <table>
                                @if(isset($userData->email))
                 <tr>
                    <td><span class="details-user"><b>Email :</b></span></td>
                   <td><span class="details_now"> {{ $userData->email }}</span></td>
                </tr>
                @endif
                @if(isset($userData->phoneNo))
                  <tr>
                    <td><span class="details-user"><b>Phone :</b></span></td>
                    <td><span class="details_now"> {{ $userData->phoneNo }}</span></td>
                  </tr>
                @endif
                @if(isset($userData->companyName))
                  <tr>
                    <td><span class="details-user"><b>Company Name :</b></span></td>
                    <td><span class="details_now"> {{ $userData->companyName }}</span></td>
                  </tr>
                @endif
                @if(isset($userData->companyUrl))
                  <tr>
                     <td><span class="details-user"><b>Company URL :</b></span></td>
                     <td><span class="details_now"> {{ $userData->companyUrl }}</span></td>
                  </tr>
                @endif
                @if(isset($userData->region->name))
                  <tr>
                   <td><span class="details-user"><b>Country :</b></span></td>
                   <td><span class="details_now"> {{ $userData->region->name }}</span></td>
                  </tr>
                @endif
                </table>
                          </div>
                    </div>
                    <div class="col-md-6">
                        <h2>Plan Details</h2>
                        <div class="">
             <table>
                        @if(isset($userData->user_plan->plans->name))
              <tr>
                <td><span class="details-user"><b>Plan Name :</b></span></td>
                <td><span class="details_now"> {{ $userData->user_plan->plans->name }}</span></td>
              </tr>
                        @endif
                        @if(isset($userData->user_plan->plans->minutes_per_month))
              <tr>
                              <td><span class="details-user"><b>Minutes / Month :</b></span></td>
                <td><span class="details_now"> {{ $userData->user_plan->plans->minutes_per_month }}</span></td>
              </tr>
                        @endif
                        @if(isset($userData->user_plan->plans->leads_per_month))
              <tr>
                             <td><span class="details-user"><b>Leads / Month :</b></span></td>
               <td><span class="details_now"> {{ $userData->user_plan->plans->leads_per_month }}</span></td>
              </tr>
                        @endif
            </table>
                      </div>
                    </div>
                    <div class="col-12">
                        <h4>Payment Details</h4>
                        <br>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="table-responsive purchase-table">
                              <div class="table table-bordered table-hover">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th scope="col">Sno.</th>
                                      <th scope="col">Date</th>
                                      <th scope="col">Valid upto</th>
                                      <th scope="col">Plan Account</th>
                                      <th scope="col"></th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php $i = 0; ?>
                                    @forelse ($userData->user_payments as $value)
                                    <?php $i++; ?>
                                    <tr class="tr{{$value->subscr_id}}">
                                      <td>{{$i}}</td>
                                      <td>{{  gmdate("m-d-Y", ($value->startDate))}}</td>
                                      <td>{{  gmdate("m-d-Y", ($value->endDate))}}</td>

                                      <td>  {{ $userData->region->countries->currencySymbol  }} {{$value->amount_paid}}</td>
                                      @if( date('Y-m-d',strtotime('+1 month',strtotime($value->created_at)) )  > date('Y-m-d'))
                                      <td>
                                        @if( $value->status != 'canceled' )
                                            <span class="badge badge-secondary text-uppercase">{{$value->status}}</span> 
                                        @else
                                         <span class="badge badge-secondary text-uppercase">{{$value->status}}</span> 
                                        @endif
                                      </td>
                                      @else
                                      <td>@if( $value->status == 'active' ) <span class="badge badge-secondary text-uppercase">Completed</span>  @else  <span class="badge badge-secondary text-uppercase">{{$value->status}}</span>  @endif</td>
                                      @endif
                                    </tr>
                                    @empty
                                    <tr>
                                      <td colspan="4">No Record Found !</td>
                                    </tr>
                                    @endforelse
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
