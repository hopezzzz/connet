<table class="table table-hover table-bordered">
<thead>
    <tr>
        <th>Sr. No</th>
        <th>Name</th>
        <th>Currency</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody class="show_records">
  <?php $i=0; ?>
  @forelse ($regions as $key => $region)
  <?php $i++; ?>
  <tr id="tableRow_{{ $region->id }}">
      <td>{{ $page + $i }}</td>
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
