@extends('layouts.admin_template')
@section('content')
<section class="content-header">
    <h1>CSV Upload<small>Register Bars</small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Bars</a></li>
        <li class="active">CSV Upload</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">CSV Upload</h3>
                </div>
                <form role="form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputFile">Bar Information CSV File</label>
                            <input type="file" id="exampleInputFile">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Bar Images CSV File</label>
                            <input type="file" id="exampleInputFile">
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="button" class="btn btn-primary" onClick="ethertest()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
        $('#business_menu_csv').addClass('active');
    </script>
@endsection