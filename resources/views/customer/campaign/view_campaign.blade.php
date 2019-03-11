@extends('layouts.customer')
@section('content')
<div class="content-wrapper">
   <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
         <li class="breadcrumb-item">
            <a  href = " javascript:void(0) ">View Campaign</a>
         </li>

      </ol>
      <!-- Icon Cards-->
      <div class="row">
         <div class="col-md-12">
          <div style="margin: 10px auto; padding: 0;" class="col-md-4 pull-right text-right clearfix">
            <a class="btn btn-primary" href="{{ URL('customer/campaigns') }}">Back</a>
          </div>
          <div class="clearfix"></div>
            <!-- Grid row -->
            <div class="row d-flex ">
               <!-- Grid column -->
               <?php
               $dayArr = array('mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday')
               ?>

                   <div class="col-md-12">
                      <table class="table campTable">
                         <thead>
                             <th style="width:30% !important;"></th>
                             <th style="width:70%;"></th>
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
                               <td><span class="details_now"> @if(isset($campData['parserOutput'])) {{  trim(preg_replace('/\s\s+/', ' ', $campData['parserOutput']  )) }} @else N/A @endif </span></td>
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
                               <td><span class="details-user"><b>Available Hours :</b></span></td>
                               <td><span class="details_now">  @if(isset($campData['availHoursFrom']) && isset($campData['availHoursTo']))
                                  {{ $campData['availHoursFrom'] }} - {{ $campData['availHoursTo'] }}
                                  @endif
                               </td>
                            </tr>
                            <tr>
                               <td><span class="details-user"><b>Call Delay Time :</b></span></td>
                               <td><span class="details_now">  @if(isset($campData['delayTime']) && isset($campData['availHoursTo']))
                                  {{ $campData['delayTime'] }}
                                  @endif
                               </td>
                            </tr>
                            <tr>
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
                            <tr><th>Sr.</th><th>Name</th><th>Phone Number</th></tr>
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
            <!-- Grid column -->
         </div>
         <!-- Grid row -->
      </div>
   </div>
</div>
</div>
@endsection
