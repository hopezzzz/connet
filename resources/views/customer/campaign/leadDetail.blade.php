@if(\Auth::user()->call_settings)
  @php $recDisplay = \Auth::user()->call_settings->call_recording_display; @endphp
@else
  @php $recDisplay = 1; @endphp
@endif
@php
  $length = $lead->call_length/60;
@endphp

@php $role = \Auth::User()->roles()->first()->name; @endphp
@if($role == 'admin') @php  $recDisplay = 1 @endphp @endif
<style media="screen">
pre{
  white-space: pre-wrap;       /* Since CSS 2.1 */
  white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
  white-space: -pre-wrap;      /* Opera 4-6 */
  white-space: -o-pre-wrap;    /* Opera 7 */
  word-wrap: break-word;
}
</style>
<div class="">
  <table class="table">

    <tr>
      <td style="width:18% !important"><b>Original Lead:</b></td>
      <td>@if(isset($lead->parse_emails->original_lead))
      <pre style="color:#000">{{ $lead->parse_emails->original_lead }}</pre>
      @else N/A @endif</td>
    </tr>
    <tr>
      <td><b>Call Script:</b></td>
      <td>@if(isset($lead->parse_emails->callScript)) {{ $lead->parse_emails->callScript }} @else N/A @endif</td>
    </tr>
    @if($recDisplay==1)
    <tr>
      <td><b>Call Recording:</b></td>
      <td><audio controls><source src="{{ $lead->recording }}" type="audio/wav"></audio>


        @if(strpos($lead->recording , 'recordings/False' ) === false ) <a href="{{$lead->recording}}" download="{{$lead->recording}}" style="position: relative;font-size: 25px;top: -16px;left: 10px;"><i data-toggle="tooltip" data-placement="top" title="Download Lead" class="fa fa-download" aria-hidden="true"></i></a> @endif

      </td>
    </tr>
    @endif
    <tr>
      <td><b>Call Time:</b></td>
      <td>{{ date("m-d-Y h:i:s A", strtotime($lead->startdate)) }}</td>
    </tr>
    <tr>
      <td><b>Call Length:</b></td>
      <td>{{ $length }} Minute(s)</td>
    </tr>
    @if(Auth::User()->roles()->first()->name=='admin')
    <tr>
      <td><b>Webmail Time:</b></td>
      <td>@if(isset($lead->parse_emails->call_logs->webmail_received)){{ date("m-d-Y h:i:s A", strtotime($lead->parse_emails->call_logs->webmail_received)) }} @else N/A @endif</td>
    </tr>
    <tr>
      <td><b>Mail Read:</b></td>
      <td>@if(isset($lead->parse_emails->call_logs->mail_read)){{ date("m-d-Y h:i:s A", strtotime($lead->parse_emails->call_logs->mail_read)) }} @else N/A @endif</td>
    </tr>
    <tr>
      <td><b>Mail Parsing:</b></td>
      <td>@if(isset($lead->parse_emails->call_logs->mail_parsed)){{ date("m-d-Y h:i:s A", strtotime($lead->parse_emails->call_logs->mail_parsed)) }} @else N/A @endif</td>
    </tr>
    <tr>
      <td><b>API Hit:</b></td>
      <td>@if(isset($lead->parse_emails->call_logs->api_hit)){{ date("m-d-Y h:i:s A", strtotime($lead->parse_emails->call_logs->api_hit)) }} @else N/A @endif</td>
    </tr>
    @endif
  </table>
</div>
