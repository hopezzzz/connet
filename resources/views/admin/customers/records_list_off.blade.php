<table class="table  table-hover table-bordered">
    <thead>
        <tr>
            <th>Sr. No</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Region</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="show_records">
    <?php $i=0; ?>
    @forelse($customers as $cust)
        <?php $i++; ?>
        <tr>
            <td>{{ $i }}</td>
            <td>{{ ucfirst($cust->firstName) }} {{ ucfirst($cust->lastName)  }}</td>
            <td>{{ $cust->email }}</td>
            <td>{{ $cust->phoneNo }}</td>
            <td>{{ $cust->region->name }}</td>
            <td>
                <a class="deleteRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-delete-modal" id="{{ $cust->id }}" data-table="{{ Crypt::encrypt('users') }}"><i class="fa fa-trash" data-toggle="tooltip" title="" data-original-title="Delete"></i></a>
                
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7">No Records Found !</td>
        </tr>
    @endforelse
    </tbody>
</table>
{{$customers->links()}}
