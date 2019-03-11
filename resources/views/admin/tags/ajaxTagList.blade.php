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
            <td>{{ $page+$i }}</td>
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
