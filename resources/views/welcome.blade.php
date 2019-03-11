<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .sbt{
                font-size: 16px;
                float: right;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md" style="font-size: 20px; font-weight:bold; color:#000;">
                    {{ Form::open(array('method'=>'post', 'url'=>'test-form-submit')) }}
                    <table class="table">
                        <tr>
                            <td>Mail To:</td>
                            <td>{{ Form::text('mailto',null) }}</td>
                        </tr>
                        <tr>
                            <td>Contact Name:</td>
                            <td>{{ Form::text('name',null)}}</td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td>{{ Form::text('email',null)}}</td>
                        </tr>
                        <tr>
                            <td>Phone:</td>
                            <td>{{ Form::text('phone',null)}}</td>
                        </tr>
                        <tr>
                            <td>Message:</td>
                            <td>{{ Form::textarea('message',null,['rows'=>3,'cols'=>30])}}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{ Form::submit('Submit',['class'=>'btn btn-primary sbt'])}}</td>
                        </tr>
                    </table>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </body>
</html>
