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
        <div style="margin: 10px auto;padding: 0;" class="col-md-12 pull-right text-right clearfix">
          <div class="form-group pull-left  col-md-4 nopadding">
              <input type="text" data-url="{{url('customer/contacts')}}" class="form-control" id="searchKey" placeholder="Search Contacts by Name , Phone Number">
          </div>
          
          <a class="btn btn-primary" href="{{URL('customer/add-contact')}}">Add New</a>
        </div>
        <div class="table-responsive recordsTable">
          <div class="table table-striped table-hover">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Sno.</th>
                  <th scope="col">Contact Name</th>
                  <th scope="col">Country Code</th>
                  <th scope="col">Phone Number</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>

                <?php $i=0; ?>
                @forelse($contacts as $contact)
                <?php $i++; ?>
                <tr>
                  <td>{{ $i }}</td>
                  <td>{{ $contact->name }}</td>
                  <td>{{ $contact->country->countryPhoneCode }} ({{ $contact->country->countryName }})</td>
                  <td>{{ $contact->contact }}</td>
                  <td>
                    <a href="{{URL('customer/edit-contact/'.Crypt::encrypt($contact->id))}}"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                    <a class="deleteRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-delete-modal" id="{{ $contact->id }}" data-table="{{ Crypt::encrypt('contacts') }}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i></a>
                  </td>

                    <!-- <a href="{{URL('delete-record')}}"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a> -->

                </tr>

                @empty
                <tr>
                  <td colspan="5">No record found !</td>
                </tr>
                @endforelse
              </tbody>
            </table>
            {{$contacts->links()}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
