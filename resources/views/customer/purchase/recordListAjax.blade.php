

<div id="plan-history" class="tab-pane  @if(isset($_GET['purchase'])) active @endif">
   <div class="table-responsive ">
       <div class="table table-striped table-hover">
         <table class="table">
           <thead>
             <tr>
               <th scope="col">Sno.</th>
               <th scope="col">Plan Name</th>
               <th scope="col">Date</th>
               <th scope="col">Valid upto</th>
               <th scope="col">No. of mins/month</th>
               <th scope="col">Leads per month</th>
               <th scope="col">Amount</th>
               <!-- <th scope="col"></th> -->
             </tr>
           </thead>
           <tbody>

             <?php $i=0; ?>
             @forelse($purchaseHistory as $value)
             @if(isset($value->users->user_plan->plans) )
             <?php $i++; ?>
             <tr class="tr{{$value->subscr_id}}">
                 <td>{{  $i +$purchase }}</td>
                 <?php if ($value->usersPlans): ?>
                   <td>{{  $value->usersPlans->name}}</td>
                   <td>{{  gmdate("m-d-Y", ($value->startDate))}}</td>
                   <td>{{  gmdate("m-d-Y", ($value->endDate)) }}</td>
                   <td>{{$value->usersPlans->minutes_per_month}}</td>
                   <td>{{$value->usersPlans->leads_per_month}}</td>
                 <?php else: ?>
                   <td>Credit balance</td>
                   <td>{{  gmdate("m-d-Y", ($value->startDate))}}</td>
                   <td>{{  gmdate("m-d-Y", ($value->endDate)) }}</td>
                   <td>NA</td>
                   <td>NA</td>
                 <?php endif; ?>


                 <td><span class="dollar"></span>{{$value->users->region->countries->currencySymbol}} {{$value->amount_paid}}</td>
             </tr>
             @else
             <tr>
               <td colspan="7">No record found !</td>
             </tr>
             @endif

             @empty
             <tr>
               <td colspan="7">No record found !</td>
             </tr>
             @endforelse
           </tbody>


         </table>
         @if(count($purchaseHistory) > 0 )
         {{$purchaseHistory->links()}}
         @endif
       </div>
     </div>
</div>
<!-- Leads Summary -->
<div id="balance-history" class="tab-pane @if(isset ($_GET['charge']) ) active @endif">
      <div class="table-responsive recordsTable">
           <div class="table table-striped table-hover">
             <table class="table">
               <thead>
                 <tr>
                   <th scope="col">Sno.</th>
                   <th scope="col">Credit Amount</th>
                   <th scope="col">Date</th>
                 </tr>
               </thead>
               <tbody>

                 <?php $i=0; ?>
                 @forelse($charge as $value)
                 @if(isset($value->users->user_plan->plans) )
                 <?php $i++; ?>
                 <tr>
                   <td>{{ $i + $charges}}</td>
                   <td><span class="dollar"></span>{{$value->users->region->countries->currencySymbol}} {{$value->amount_paid}}</td>
                   <td>{{  gmdate("m-d-Y", ($value->startDate))}}</td>
                 </tr>

                 @endif

                 @empty
                 <tr>
                   <td colspan="3">No record found !</td>
                 </tr>
                 @endforelse
               </tbody>


             </table>
             @if(count($charge) > 0 )
             {{$charge->links()}}
             @endif
           </div>
         </div>
   </div>
