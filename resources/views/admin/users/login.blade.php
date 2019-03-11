<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Main CSS-->
        <link href="{{ asset('assets/admin/css/toaster.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/admin/css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet" type="text/css">
        <!-- Font-icon css-->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>{{ config('app.applicationame') }} | Login</title>
    </head>
    <body>
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>
    <section class="login-content">
        <div class="logo">
            <h1><a class="login-logo" href="{!! url('') !!}" ><img src="<?php echo URL :: to(config('app.logo')); ?>" alt="Logo"/></a></h1>
        </div>
        <div class="login-box">
            {{ Form::open(array('url' => 'admin/user/login','id' => 'login-form','class' => 'login-form','autocomplete' => 'off')) }}
                <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
                <div class="form-group">
                    <label class="control-label">USERNAME</label>
                    <input class="form-control" type="text" name="email" placeholder="Email" autofocus  >
                </div>
                <div class="form-group">
                    <label class="control-label">PASSWORD</label>
                    <input class="form-control" type="password" name="password" placeholder="Password" >
                </div>
                <!-- <div class="form-group">
                    <div class="utility">
                        <div class="animated-checkbox">
                            <label>
                                <input type="checkbox"><span class="label-text">Stay Signed in</span>
                            </label>
                        </div>
                     
                    </div>
                </div> -->
                <div class="form-group btn-container">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
                </div>
            {{ Form::close() }}
            <form class="forget-form" action="index.html">
                <h3 class="login-head"><i class="fa fa-lg fa-fw fa-lock"></i>Forgot Password ?</h3>
                <div class="form-group">
                    <label class="control-label">EMAIL</label>
                    <input class="form-control" type="text" placeholder="Email">
                </div>
                <div class="form-group btn-container">
                    <button class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>
                </div>
                <div class="form-group mt-3">
                    <p class="semibold-text mb-0"><a  href = " javascript:void(0) " data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
                </div>
            </form>
        </div>
    </section>

    <!-- Essential javascripts for application to work-->
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.validate.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/form-validate.js') }}"></script>
    <script src="{{ asset('assets/admin/js/additional-method.js') }}"></script>
    <script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/main.js') }}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset('assets/admin/js/plugins/pace.min.js') }}"></script>
    <script type="text/javascript">
        // Login Page Flipbox control
        $('.login-content [data-toggle="flip"]').click(function() {
      	     $('.login-box').toggleClass('flipped');
      	      return false;
          });
    </script>
	<div class="loader_panel" style="display:none;">
	       <div class="loader"></div>
	</div>
    </body>
</html>
