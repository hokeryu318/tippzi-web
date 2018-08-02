@extends('layouts.admin_template')
@section('content')
<section class="content-header">
    <h1>Manual Register<small>Register Bars</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Bars</a></li>
        <li class="active">Manual Register</li>
    </ol>
</section>
<section class="content">
    
</section>
    <!-- jQuery 3 -->
    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('bower_components/admin-lte/dist/js/adminlte.min.js')}}"></script>

    <script>
        $('#business_menu').addClass('active');
        $('#business_menu_manual').addClass('active');
    </script>
@endsection