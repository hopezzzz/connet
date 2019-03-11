<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Open Graph Meta-->
        <title>Direct Connect</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Main CSS-->
        <link href="{{ asset('assets/admin/css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/admin/css/toaster.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/loader.css') }}" rel="stylesheet" type="text/css">
        
        <link rel="icon" href="{{ asset('assets/favicon.png') }}" type="image/gif" sizes="16x16">
        <!-- Font-icon css-->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
    </head>
    <body class="app sidebar-mini rtl">
