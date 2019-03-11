
<input type="hidden" name="frontreq" value="1">
 <div class="modal-content">
    <div class="modal-header">
       <h4 class="modal-title">Add New Contact</h4>
       <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Contact Name <span class="star">*</span></label>
                {!! Form::text('name',$data['name'],['placeholder'=>"Name",'class'=>'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Contact Email <span class="star">*</span></label>
                {!! Form::text('email',$data['email'],['placeholder'=>"Contact Email",'class'=>'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Role/Title </label>
                {!! Form::text('role',$data['role'],['placeholder'=>"Role/Title",'class'=>'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Department/Location </label>
                {!! Form::text('dept',$data['dept'],['placeholder'=>"Department/Location",'class'=>'form-control']) !!}
                @if(isset($recordId))
                {!! Form::hidden('recordId',$recordId) !!}
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Country Code <span class="star">*</span></label>
                {!! Form::select('code',$countryCode,$data['code'],['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Phone Number <span class="star">*</span></label>
                {!!  Form::text('number',$data['number'],['placeholder'=>"Phone Number",'class'=>'form-control']) !!}
            </div>
        </div>
      </div>

      <?php
      $daysArr = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
      ?>
      <div class="row">
        <div class="col-md-12" style="display:none">
          <div class="form-group">
                <label for="">Schedule <span class="star">*</span></label>
          </div>
          <div class="">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Days</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @for($i=0;$i<=6;$i++)
                <tr>
                  <td>{{ $daysArr[$i] }}</td>
                  <td>
                    <input class="form-control validate timepicker" type="text" name="dayFrom[]" value="@if(isset($data['schedule'][$i]->from)) {{$data['schedule'][$i]->from}} @endif" id="dayF{{$i}}">
                    </td>
                  <td><input class="form-control validate timepicker" type="text" name="dayTo[]" value="@if(isset($data['schedule'][$i]->to)) {{$data['schedule'][$i]->to}} @endif" id="dayT{{$i}}"></td>

                  <td><span class="daySts" style="font-size: 30px; color: #007bff;" data-val="@if(isset($data['schedule'][$i]->status)) {{ $data['schedule'][$i]->status }} @else 0 @endif"><i class="fa @if(isset($data['schedule'][$i]->status) && $data['schedule'][$i]->status!=0) fa-toggle-on @else fa-toggle-off @endif" data-toggle="tooltip" title="" data-original-title="Change Status"></i></span><input class="sts-input" type="hidden" name="dayStatus[]" value="@if(isset($data['schedule'][$i]->status)) {{ $data['schedule'][$i]->status }} @else 0 @endif"></td>
                </tr>
                @endfor
              </tbody>
            </table>
          </div>
        </div>
      </div>

       <div class="text-right">
          <button type="button" class="btn btn-primary cancel-now" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Yes</button>
       </div>
    </div>

 </div>
