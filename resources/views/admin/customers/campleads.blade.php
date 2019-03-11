<table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Lead ID</th>
                                    <th>Call Length</th>
                                    <th>Call Date</th>
                                    <th>Call Summary</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="show_records">
                            <?php $i=0; ?>
                            @if(isset($leadsData))
                                @forelse($leadsData as $leads)
                                @php  
                                    $i++;
                                    $length = $leads->cost/60;  
                                @endphp
                                <tr>
                                  <td>{{  $i + $page }}</td>
                                  <td>{{ $leads->lead_id }}</td>
                                  <td><span class="blue-color"><i data-toggle="tooltip" data-placement="top" title="Total Call Length: {{ $length }} Minute(s)" class="fa fa-info-circle"></i></span></td>
                                  <td>{{ date("m-d-Y h:i:s A", strtotime($leads->startdate)) }}</td>
                                  
                                  <td>
                                      <audio controls>
                                          <source src="{{ $leads->recording }}" type="audio/wav">
                                      </audio>

                                  </td>
                                  
                                  <td>
                                    <a class="blue-color getLeadData" data-url="{{ URL('get-lead-data') }}/{{ $leads->id }}" href="javascript:void(0)" data-toggle="modal" data-target="#leadDetails"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View Lead Details"></i></a>
                                    &nbsp;
                                    <a href="{{$leads->recording}}" download="{{$leads->recording}}"><i data-toggle="tooltip" data-placement="top" title="Download Lead" class="fa fa-download" aria-hidden="true"></i></a>
                                  </td>
                              </tr>
                              @empty
                              <tr>
                                  <td colspan="6">No Record Found !</td>
                              </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="6">No Record Found !</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{ $leadsData->links() }}