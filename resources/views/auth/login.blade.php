<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/toaster.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet">
      <link rel="icon" href="{{ asset('assets/favicon.png') }}" type="image/gif" sizes="16x16">
    <style media="screen">

    .spinner {
        width: 60px;
        height: 60px;
        background-color: #007bff;
        margin: 100px auto;
        -webkit-animation: sk-rotateplane 1.2s infinite ease-in-out;
        animation: sk-rotateplane 1.2s infinite ease-in-out;
        position: absolute;
        left: 0;
        right: 0;
        bottom: 35%;
    }

    @-webkit-keyframes sk-rotateplane {
      0% { -webkit-transform: perspective(120px) }
      50% { -webkit-transform: perspective(120px) rotateY(180deg) }
      100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }
    }

    @keyframes sk-rotateplane {
      0% {
        transform: perspective(120px) rotateX(0deg) rotateY(0deg);
        -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg)
      } 50% {
        transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
        -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg)
      } 100% {
        transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
        -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
      }
    }
    .loader_div{
      position: fixed;
        background: #fff;
        width: 100%;
        left: 0;
        right: 0;
        top: 0;
        height: 100%;
        z-index: 9999;
    }
    </style>
</head>

<body>
  <div class="loader_div" style="display:none">
    <div class="spinner"></div>
  </div>
    <div class="register-main-sec">
        <div class="container">
            <div class="register-bx">
                <div class="register-inner-bx">
                <div class="logo">
                    <a href="javascript:void(0)"><img src="{{ asset('assets/frontend/img/logo-r.png')}}" /></a>
                </div>
                <div class="form-gm btn-sec">
                <form id="login-customer" method="post" action="{{ route('login') }}" autocomplete="off">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <input  autocomplete="off" class="form-control form-control-lg"  name="email" type="text" placeholder="User Name">
                        <input  autocomplete="new-password" class="form-control" type="password" name="password" placeholder="Password">
                    </div>
					  <div class="login-back">
					   <div class="pull-left">
                    <button type="submit" class="btn btn-primary">
                        Log In
                    </button>
					</div>
					<div class="pull-right">

					<a class="btn btn-primary sign-in-back" href="{{url('sign-up')}}">  Back to signup</a>
					</div>
					</div>
					<div class="forget-now">

                    <div class="btn-sec login-btn">
                        <a class="forgot-password" href="{{url('password/reset')}}">Forgot Password ?</a>
                    </div>
					</div>
                </form>
                </div>
                    </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="{{ asset('assets/admin/js/jquery.toast.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/form-validate.js') }}"></script>

</body>

</html>
