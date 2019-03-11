<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>Sr. No</th>
            <th>Plan Name</th>
            <th>Minutes/Month</th>
            <th>Leads/Month</th>
            <th>Prices</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="show_records">
    <?php $i=0; ?>
    @forelse($plans as $plan)
        <?php $i++; ?>
        <tr>
            <td>{{ $page+$i }}</td>
            <td>{{ $plan->name }}</td>
            <td>{{ $plan->minutes_per_month }}</td>
            <td>{{ $plan->leads_per_month }}</td>
            <td>
            <?php $planPrices = ''; ?>
            @foreach($plan->plan_prices as $price)
                <?php $planPrices .= $price->plan_currency->currencyCode.' '.$price->amount.', '; ?>
            @endforeach
            {{ rtrim($planPrices,', ') }}
            </td>
            <td>
                <a href="{{ $url.'/edit-plan/'.Crypt::encrypt($plan->id) }}"><span><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i> </span></a>
                <a href="{{ $url.'/view-plan/'.Crypt::encrypt($plan->id) }}"><span><i class="fa fa-eye" data-toggle="tooltip" title="view"></i> </span> </a>
                <a class="deleteRecord" href="javascript:void(0)" data-toggle="modal" data-target="#confirm-delete-modal" id="{{ $plan->id }}" data-table="{{ Crypt::encrypt('plans') }}"><span><i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i> </span></a></td>
        </tr>

        @empty
        <tr>
          <td colspan="6">No Records Found !</td>
        </tr>
    @endforelse
    </tbody>
</table>
{{$plans->links()}}
