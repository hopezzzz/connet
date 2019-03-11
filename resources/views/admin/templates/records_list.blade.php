<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>Sr. No</th>
            <th>Template Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="show_records">
    <?php $i=0; ?>
    @forelse($templates as $temp)
        <?php $i++; ?>
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $temp->title }}</td>
            <td>
                <a href="{{ $url.'/edit-template/'.Crypt::encrypt($temp->id) }}" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                <a href="{{ $url.'/view-template/'.Crypt::encrypt($temp->id) }}" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>
            </td>
        </tr>
    @empty
      <tr>
        <td colspan="6">No Records Found !</td>
      </tr>
    @endforelse
    </tbody>
</table>
{{$templates->links()}}
