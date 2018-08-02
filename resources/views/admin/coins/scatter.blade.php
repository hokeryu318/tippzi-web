@extends('layouts.admin_template')
@section('content')
<section class="content-header">
    <h1>Scatter Coins<small></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Coin Management</a></li>
        <li class="active">Scatter Coins</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
          <!-- Input addon -->
            <div class="box box-info">
                {!! Form::open(array('id' => 'form_scatter','url'=>'coin/scatter_post', 'accept-charset' => 'UTF-8', 'novalidate')) !!}
                <div class="box-header with-border">
                    <h3 class="box-title">Scattering</h3>
                </div>
                <div class="box-body">
                    <h4>Location</h4>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                        <input type="text" class="form-control" name="longitude" id="txt_long" placeholder="Longitude" value="51.508742" required>
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                        <input type="text" class="form-control" name="latitude" id="txt_lat" placeholder="Latitude" value="-0.120850" required>
                    </div>
                    <br>
                    <h4>Coin Count</h4>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-bitcoin"></i></span>
                        <input type="number" class="form-control" name="coinct" placeholder="Coin Count" id="coin_ct" required>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-default">Cancel</button>
                    <button type="submit" class="btn btn-info pull-right" onClick="moveCenter()">Submit</button>
                </div>
                {{ Form::close()}}
            </div>
        </div>
    </div>
    <div id="map" style="width:800px;height:800px">
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
        $('#coin_menu').addClass('active');
        $('#coin_menu_scatter').addClass('active');
        var map;
        function myMap() {
            var mapOptions = {
                center: new google.maps.LatLng(51.508742,-0.120850),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.HYBRID
            }
            map = new google.maps.Map(document.getElementById("map"), mapOptions);
            loadCoinPositions();
        }
        function moveCenter(){
            // alert('');
            var longitude = Number($('#txt_long').val());
            var latitude = Number($('#txt_lat').val());
            var center = new google.maps.LatLng(longitude, latitude);
            map.setCenter(center);
        }
        function loadCoinPositions(){
            var pos, marker;
            @foreach($coin_positions as $pos)
                pos = new google.maps.LatLng('{{$pos->longitude}}', '{{$pos->latitude}}');
                marker = new google.maps.Marker({position: pos});
                marker.setMap(map);
            @endforeach
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?callback=myMap"></script>
@endsection