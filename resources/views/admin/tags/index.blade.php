@extends('layouts.admin')
@section('content')
    <main class="app-content">
        <div class="app-title">
            <div>
                <h1><i class="fa fa-list"></i> Tags</h1>
            </div>
            <ul class="app-breadcrumb breadcrumb">
                <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
                <li class="breadcrumb-item">Tags</li>
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
                          <a type="button" href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#add-new-tag">Add Tag</a>
                        </div>
                    </div>
                </div>
                <div class="tile">
                    <div class="tile-body recordsTable">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Tag</th>
                                    <th>Created at</th>
                                    <th>Added By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="show_records">
                            <?php $i=0;  $status = array('Inactive' , 'Active' );?>
                            @forelse($parseTag as $tags)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td class="tagName">{{ $tags->tagName }}</td>
                                    <td>{{ date('m-d-Y',strtotime($tags->created_at)) }}</td>
                                    @if(count($tags->user) > 0 )
			            <td>
			            @if($tags->addedBy == 1)
			            Admin
			            @else
			            {{ ucwords($tags->user->firstName .' '.$tags->user->lastName) }}
			            @endif
			            </td>
			            @else
			            <td> <i>NA</i> </td>
			
			            @endif
                                    <td>
                                        <a href="javascript:void(0)" data-ref="{{ $tags->id }}" data-toggle="tooltip" title="Edit" class="tagEdit"><i class="fa fa-pencil"></i></a>
                                        <a class="deleteRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-delete-modal" id="{{ $tags->id }}" data-table="{{ Crypt::encrypt('parsetags') }}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i></a></td>    
                                </tr>
                            @empty
                            	<tr>
                            		<td colspan="6">No Records Found !</td>
                            	</tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{$parseTag->links()}}
                    </div>
                </div>
            </div>
        </div>
    </main>

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


    <div id="add-new-tag" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <form class="addTag" id="add-new-tag-form" action="{{ URL::to('add-new-tag') }}" method="post">


            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Tag</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">

                          <input type="text" name="tagName" class="form-control" placeholder="Enter Tag Name">
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary cancel-now" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Yes</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="tagRef" class="tagRef">
            </form>
        </div>
    </div>

    <!-- Delete Ends -->
@endsection
