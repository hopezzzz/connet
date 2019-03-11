<div class="table table-striped table-hover">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Sno.</th>
                <th scope="col">Plan Name</th>
                <th scope="col">Date</th>
                <th scope="col">Valid upto</th>
                <th scope="col">No. of Minutes/Month</th>
                <th scope="col">Leads per month</th>
                <th scope="col">Amount</th>
                <th scope="col">info</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
               <?php $i=0; ?>
                <?php $i++; ?>
                     @php $sub_id = $userPlan->sub_id @endphp
                    <tr id="tr{{$userPlan->sub_id}}">
                        <td>{{$i}}</td>
                        <td>{{ $userPlan->usersPlans->name }}</td>
                        <td>{{ gmdate("m-d-Y", ($userPlan->startDate))}}</td>
                        <td class="valid_up_to" data-ref="{{ gmdate('d-m-Y', ($userPlan->endDate)) }}">{{ gmdate("m-d-Y", ($userPlan->endDate)) }}</td>

                        <td>{{ $userPlan->usersPlans->minutes_per_month }}</td>
                        <td>{{ $userPlan->usersPlans->leads_per_month }}</td>
                        <td>
                            <span class="text-right"></span> {{ $userPlan->users->region->countries->currencySymbol }} {{$userPlan->amount_paid}}
                        </td>
                        @php $canceled_at = 'Active' @endphp
                        @if($userPlan->canceled_at == 1 )
                        @php $canceled_at = 'Subscrition cancelled at '.gmdate(''d-m-Y', ($userPlan->cancelDate)) @endphp
                        @endif
                        @if($userPlan->canceled_at == 2 )
                        @php $canceled_at = 'Subscription will be cancelled at the end of current period on '.gmdate('d-m-Y', ($userPlan->cancelDate)) @endphp
                        @endif
                        <td><i class="fa fa-info-circle green" data-toggle="tooltip" title="{{$canceled_at}}"></i></td>
                        <td> <span class="badge badge-secondary text-uppercase">{{ $userPlan->status}}</span></td>
                        @if(isset($userPlan->endDate))
                        @if( gmdate('Y-m-d', ($userPlan->endDate)) >= date('Y-m-d') )
                        <td>
                            @if( strtolower($userPlan->status) != 'canceled' )
                            <a class="unsubscribePlan" href="javascript:void(0)" data-ref="{{$userPlan->sub_id}}"> <img src="{{ asset('assets/btn_unsubscribe_LG.gif')}}"> </a>
                            @else
                            <span class="badge badge-secondary text-uppercase">{{ $userPlan->status}}</span> @endif
                        </td>
                         @else
                          <td> <span class="badge badge-secondary text-uppercase">{{ $userPlan->status}}</span> </td>
                        @endif
                        @endif

                    </tr>

        </tbody>
    </table>


</div>
