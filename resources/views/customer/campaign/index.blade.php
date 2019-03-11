@extends('layouts.customer')
@section('content')
<style>
  .green{ color: green; }
  .blue{ color: #ec2235; }
</style>
<div class="content-wrapper">
<div class="container-fluid">
<!-- Breadcrumbs-->
<ol class="breadcrumb">
  <li class="breadcrumb-item">
    <a href="javascript:void(0)">Campaigns</a>
  </li>
</ol>
<!-- Icon Cards-->
<div class="row">
  <div class="col-md-12">
    <div style="margin: 10px auto;padding: 0;" class="col-md-12 pull-right text-right clearfix">
      <div class="form-group pull-left  col-md-4 nopadding">
          <input type="text" data-url="{{url('customer/campaigns')}}" class="form-control" id="searchKey" placeholder="Search Campaigns by Name , Email">
      </div>

      <a class="btn btn-primary" href="{{URL('customer/add-new-campaign')}}">Add New</a>
    </div>

    <div class="table-responsive recordsTable">
      <div class="table table-striped table-hover">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Sr.</th>
              <th scope="col">Campaign Name</th>
              <th scope="col">Email Address</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>

            <?php $i=0; ?>
            @forelse($camps as $camp)
            <?php $i++; ?>
            <tr>
              <td>{{ $i }}</td>
              <td>{{ $camp->title }}</td>
              <td>{{ $camp->email }}</td>
              <td>@if($camp->step==4) <i class="fa fa-check-circle green" data-toggle="tooltip" title="Campaign Completed"></i> @else <i class="fa fa-info-circle blue" data-toggle="tooltip" data-placement="top" title="Please complete all the steps to make this campaign working."></i> @endif</td>
              <td>
                <a href="{{URL('customer/edit-campaign/'.Crypt::encrypt($camp->id))}}"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>

                <a href="{{URL('customer/campaign-leads')}}/{{ Crypt::encrypt($camp->id) }}"><i class="fa fa-list" data-toggle="tooltip" data-placement="top" title="Leads"></i></a>

                <a class="statusRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-status-modal" id="{{ $camp->id }}" data-status="{{ $camp->status }}" data-table="{{ Crypt::encrypt('campaigns') }}"><i class="@if($camp->status==1) fa fa-toggle-on @else fa fa-toggle-off @endif" data-toggle="tooltip" title="Change Status"></i></a>

                @if($camp->step==4)<a href="{{URL('customer/view-campaign/'.Crypt::encrypt($camp->id))}}"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View Campaign"></i></a> @endif

                <a class="deleteRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-delete-modal" id="{{ $camp->id }}" data-table="{{ Crypt::encrypt('campaigns') }}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i></a></td>

              </td>

            </tr>

            @empty
            <tr>
              <td colspan="5">No record found !</td>
            </tr>
            @endforelse
          </tbody>
        </table>
        {{$camps->links()}}
      </div>
    </div>
  </div>

</div>
</div>
</div>
@endsection
