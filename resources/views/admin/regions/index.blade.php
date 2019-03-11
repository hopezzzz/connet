@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-globe"></i> Regions</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Regions</li>
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
                        <div class="form-group pull-left  col-md-4 nopadding">
                            <input type="text" data-url="{{url('admin/regions')}}" class="form-control" id="searchKey" placeholder="Search Regions by Name">
                        </div>
                        <div class="form-group text-right">
                            <a href="javascript:void(0)" class="btn btn-primary addUpdateRecord" data-url="{{ URL(config('app.admintemplatename').'/get-region-data') }}" data-id="">Add Region</a>
                        </div>
                    </div>
                </div>
                <div class="tile">
                    <div class="tile-body  table-responsive recordsTable">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Region Name</th>
                                    <th>Currency</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="show_records">
                                @forelse ($regions as $key => $region)
                                <tr id="tableRow_{{ $region->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $region->name }}</td>
                                    <td>{{ $region->countries->currencyName }}</td>
                                    <td><a href="javascript:void(0)" class="addUpdateRecord" data-id="{{ \Crypt::encrypt($region->id) }}" data-url="{{ URL(config('app.admintemplatename').'/get-region-data') }}" title="View/Update Record"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></a>
                                    <a class="deleteRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-delete-modal" id="{{ $region->id }}" data-table="{{ Crypt::encrypt('regions') }}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i></a></td>
                                @empty
                                <tr>
                                     <td colspan="4">No Records Found !</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $regions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Modal -->
    <div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span id="titleModal">Add</span> Region</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- Add Model Ends -->
    <!-- Delete Modal -->
    <div id="confirm-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Delete</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <h6>Are you sure you want to delete this record ?</h6>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary cancel-now" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary deleteRecordBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Ends -->
@endsection
