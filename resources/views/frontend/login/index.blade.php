<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
		<link href="{{ asset('assets/'.config("app.frontendtemplatename").'/css/bootstrap.min.css') }}" rel="stylesheet">
		  <link href="{{ asset('assets/'.config("app.frontendtemplatename").'/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700" rel="stylesheet">
    <meta name="_token" content="{{ csrf_token() }}">
<link rel="icon" href="{{ asset('assets/favicon.png') }}" type="image/gif" sizes="16x16">

</head>

<body>
    <div class="register-main-sec">
        <div class="container">
            <div class="register-bx login-section">
                <div class="register-inner-bx">
                <div class="logo">
                    <a href="javascript:void(0)"><img src="{{ asset('assets/'.config("app.frontendtemplatename").'/img/logo-r.png') }}" /></a>
                </div>
                <div class="btn-sec">
                    <a class="btn-cutsomer btn-primary" href="{!! URL('/sign-up') !!}"> Signup </a>
                    <a class="btn-cutsomer btn-primary" href="{!! URL('/login') !!}"> Log In </a>

                </div>
                    </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
