<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.applicationame') }}</title>
    <link href="{{ asset('assets/'.config("app.frontendtemplatename").'/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/'.config("app.frontendtemplatename").'/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/'.config("app.frontendtemplatename").'/css/custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/admin/css/toaster.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/favicon.png') }}" type="image/gif" sizes="16x16">
</head>
<body>
