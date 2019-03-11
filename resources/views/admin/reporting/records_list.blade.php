<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>Sr. No</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Plan Name</th>
            <th>Region</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="show_records">
    <?php $i=0; ?>
    @forelse($customers as $cust)
        <?php $i++; ?>
        
        <tr>
            <td>{{ $page+$i }}</td>
            <td>@if(isset($cust->firstName)){{ ucfirst($cust->firstName) }} @endif @if(isset($cust->lastName)){{ ucfirst($cust->lastName) }} @endif</td>
            <td>@if(isset($cust->email)){{ $cust->email }} @endif</td>
            <td>@if(isset($cust->phoneNo)){{ $cust->phoneNo }} @endif</td>
            <td>@if(isset($cust->user_plan->plans->name)){{ $cust->user_plan->plans->name }} @endif</td>
            <td>@if(isset($cust->region->name)){{ $cust->region->name }} @endif</td>
            <td>
                <a href="{{ $url.'/campaign-reports/'.Crypt::encrypt($cust->id) }}"data-toggle="tooltip" title="Campaign Reports"><span><i class="fa fa-newspaper-o"></i> </span></a>

                <a href="{{ $url.'/lead-reports/'.Crypt::encrypt($cust->id) }}" data-toggle="tooltip" title="Lead Reports"><span><i class="fa fa-eye"></i> </span></a>
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
