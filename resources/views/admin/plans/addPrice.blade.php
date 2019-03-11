<tr>
    <td class="form-group">
        {!!  Form::text('price[]',null,['placeholder'=>"Price",'class'=>'form-control priceForm parseInt ']) !!}
    </td>
    <td class="form-group creditfield">
        {!!  Form::text('credit[]',null,['placeholder'=>"Credit",'class'=>'form-control creditForm']) !!}
    </td>
    <td class="form-group creditfield">
        {!!  Form::text('per_min_cost[]',null,['placeholder'=>"Cost per minute",'class'=>'form-control perMinCostForm']) !!}
    </td>
    <td class="form-group">
        {!! Form::select('currency_id[]',$currencyList,null, ['class'=>'form-control currency_id']) !!}
    </td>
    <td>
        <span class="delete-price"><i class="fa fa-close"></i></span>
    </td>
</tr>
