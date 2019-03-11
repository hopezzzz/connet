@if(\Auth::user()->call_settings)
  @php $recDisplay = \Auth::user()->call_settings->call_recording_display; @endphp
@else
  @php $recDisplay = 1; @endphp
@endif
<table class="table" width="100%">
  <thead>
    <tr>
      <th scope="col" width="6%">Sr.</th>
      <th scope="col" width="7%">Lead ID</th>
      <th scope="col" width="9%">Call Length</th> 
      <th scope="col" width="15%">Call Date</th>
      <th scope="col" width="12%">Agent Name</th>
      @if($recDisplay==1)<th scope="col" width="20%">Call Summary</th>@endif
      <th scope="col" width="10%">Rating</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php $i=0; ?>
  @forelse($leadsData as $leads)
  @php
  $i++;
  $length = $leads->call_length/60;
  @endphp
  <tr id="{{ $leads->id }}">
      <td>{{ $i }}</td>
      <td>{{ $leads->lead_id }}</td>
      <td><span class="blue-color"><i data-toggle="tooltip" data-placement="top" title="Total Call Length: {{ $length }} Minute(s)" class="fa fa-info-circle"></i></span></td>
      <td>{{ date("m-d-Y h:i:s A", strtotime($leads->startdate)) }}</td>
      <td>@if($leads->agent) {{ $leads->agent }} @else N/A @endif</td>
      @if($recDisplay==1)
      <td>
          <audio controls>
              <source src="{{ $leads->recording }}" type="audio/wav">
          </audio>

      </td>
      <td class="rateTD" title="@if($leads->rating==1) Poor @elseif($leads->rating==2) Good @elseif($leads->rating==3) Excellent @endif">
      @for($i=1; $i<=3; $i++)
        @if($i<=$leads->rating) 
          <i class="stars rate-blue fa fa-star"></i>
        @else
          <i class="stars fa fa-star-o"></i>
        @endif  
      @endfor
    </td>
      @endif
      <td>
        <a class="blue-color getLeadData" data-url="{{ URL('get-lead-data') }}/{{ $leads->id }}" href="javascript:void(0)" data-toggle="modal" data-target="#leadDetails"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View Lead Details"></i></a>
        &nbsp;
        @if($recDisplay==1)
          @if(strpos($leads->recording , 'recordings/False' ) === false ) <a href="{{$leads->recording}}" download="{{$leads->recording}}"><i data-toggle="tooltip" data-placement="top" title="Download Lead" class="fa fa-download" aria-hidden="true"></i></a>@endif 
        @endif
        <a class="blue-color rateLead" data-lead-id="{{ Crypt::encrypt($leads->id) }}" href="javascript:void(0)" data-toggle="modal" data-target="#leadRating"><i class="fa fa-star" data-toggle="tooltip" data-placement="top" title="View Lead Rating"></i></a>
      </td>
  </tr>
  @empty
  <tr>
      <td colspan="7">No Record Found !</td>
  </tr>
  @endforelse
  </tbody>
</table>
{{ $leadsData->links() }}
      