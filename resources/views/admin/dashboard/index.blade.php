@extends('layouts.admin')
@section('content')
  <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-3">
          <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
            <div class="info">
              <h4>Customers</h4>
              <p><b>@if($records['customers']!=0) {{ $records['customers'] }} @else No Records ! @endif</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small warning coloured-icon"><i class="icon fa fa-globe fa-3x"></i>
            <div class="info">
              <h4>Regions</h4>
              <p><b>@if($records['regions']!=0) {{ $records['regions'] }} @else No Records ! @endif</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small info coloured-icon"><i class="icon fa fa-list fa-3x"></i>
            <div class="info">
              <h4>Plans</h4>
              <p><b>@if($records['plans']!=0) {{ $records['plans'] }} @else No Records ! @endif</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small danger coloured-icon"><i class="icon fa fa-star fa-3x"></i>
            <div class="info">
              <h4>Campaigns</h4>
              <p><b>@if($records['campaigns']!=0) {{ $records['campaigns'] }} @else No Records ! @endif</b></p>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection
