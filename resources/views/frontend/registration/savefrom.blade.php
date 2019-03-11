<div class="row">
    <div class="col-md-12">
        <div class="tile">
            {{ Form::model($recordDetail,array('url' => config('app.admintemplatename').'/add-region','id' => 'region-form','class' => 'region-form','autocomplete' => 'off')) }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="">Region Name</label>
                        {!!  Form::text('name',null,['placeholder'=>"Region Name",'class'=>'form-control']); !!}
                        {!!  Form::hidden('id',$recordID); !!}

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Currency</label>
                            {!! Form::select('currency',$currency,null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary submitBtn">Save</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
