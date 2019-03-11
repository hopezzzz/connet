<table class="table table-striped table-hover">
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
        <td>{{ $page+$i }}</td>
      <td>{{ $contact->name }}</td>
      <td>{{ $contact->country->countryPhoneCode }} ({{ $contact->country->countryName }})</td>
      <td>{{ $contact->contact }}</td>
      <td>
        <a href="{{URL('customer/edit-contact/'.Crypt::encrypt($contact->id))}}"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
        <a class="deleteRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-delete-modal" id="{{ $contact->id }}" data-table="{{ Crypt::encrypt('contacts') }}"><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i></a>
        <!-- <a href="{{URL('delete-record')}}"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>  -->
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="5">No record found !</td>
    </tr>
    @endforelse
  </tbody>
</table>
{{$contacts->links()}}
