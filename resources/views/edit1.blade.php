@include('include/header')
<title>Edit Profile</title>
<body>

<div class="container-fluid stage_content">
    <div class="header row">
        <div class="col-logo">
            <div class="logo">
                <a href="{{ route('index') }}">
                <img src="{{ asset('images/logo.png') }}">
                </a>
            </div>
        </div>
    </div>

    <div class="t-content">
        <div class="row reg-content">
            <div class="col-lg-4 col-md-2 col-sm-2">
            </div>
            <div class="col-lg-4 col-md-8 col-sm-8">

                {{--<form action="{{route('bar_register.stage1')}}" method="POST">--}}
                <form action="{{ route('bar.update1') }}" method="POST" id="submit-form">
                    @csrf
                    <div class="row t-input-row">
                        <img src="{{ asset('images/stage1.png') }}">
                        <p>What are your contract details?</p>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="name" placeholder="The Hoxton Pony" value="{{ get_template_variable('name', $bar) }}"/>
                            @if($errors->has('name'))
                                <span class="c-error-block">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <input type="text" name="post_code" placeholder="Post code" value="{{ get_template_variable('post_code', $bar) }}" id="postal-code">
                            @if($errors->has('post_code'))
                                <span class="c-error-block">{{ $errors->first('post_code') }}</span>
                            @endif
                        </div>
                        <div class="col-xs-6">
                            <button type="button" class="lookup" onclick="onGetAddress();">Lookup</button>
                            @if($errors->has('post_code'))
                                <span class="c-error-block" style="color: #000;">a</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input id="address" name="address" style="margin-bottom:10px;" value="{{ get_template_variable('address', $bar) }}" placeholder="Enter your address"/>
                            @if($errors->has('address'))
                                <span class="c-error-block">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="email" placeholder="Your business email" value="{{ get_template_variable('email', $bar) }}"/>
                            @if($errors->has('email'))
                                <span class="c-error-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="telephone" placeholder="Your business telephone" value="{{ get_template_variable('telephone', $bar) }}"/>
                            @if($errors->has('telephone'))
                                <span class="c-error-block">{{ $errors->first('telephone') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="website" placeholder="Your business website" value="{{ get_template_variable('website', $bar) }}"/>
                            @if($errors->has('website'))
                                <span class="c-error-block">{{ $errors->first('website') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <button type="button" onclick="previous();" class="round_btn ">Previous</button>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" onclick="getGeoInfo();" class="round_btn">Continue</button>
                        </div>
                    </div>
                    <input type="hidden" name="latitude" id="latitude" value="{{ get_template_variable('latitude', $bar) }}"/>
                    <input type="hidden" name="longitude" id="longitude" value="{{ get_template_variable('longitude', $bar) }}"/>
                    <input type="hidden" name="id" value="{{ $id }}" />
                </form>

                <button id="modal-btn"  data-toggle="modal" data-target="#exampleModal" style="display: none"></button>
            </div>
            <div class="col-lg-4 col-md-2 col-sm-2">
            </div>

        </div>
        @include('include.footer')

    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-address-header">
                    <h5 class="modal-title modal-address-title" id="exampleModalLabel">Select Your Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal-close" style="display: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-address-body" id="modal-body">

                </div>
            </div>
        </div>
    </div>

</div>
</body>

<script>
    $(document).ready(function(){

        $("#show").click(function(){
            $("#address").show();
        });
    });

    function previous()
    {
        location.href = "{{ route('user.dashboard') }}";
    }

    function onGetAddress() {
        var postalCode = $('#postal-code').val();
        postalCode = postalCode.trim();
        postalCode = postalCode.toUpperCase();

        var purl = "https://api.getaddress.io/find/" + postalCode + "?api-key=_GJ_sMycoUOy1_g7W2lavw10914";

        // var purl = 'https://dev.virtualearth.net/REST/v1/Locations?countryRegion=JA&postalCode='
        // + postalCode
        // + '&o=json&key=AoyhuvvuNi0LJYoJhgs0NIl4sTLl_aB_ew7NZr3bPhw6yLk1bIXywCbRVwhEIPfB&c=ja';
        // $.ajax({
        //     type: "GET",
        //     url: purl,
        //     dataType: "json",
        //     success: function (result) {
        //         console.log(result);
        //     }
        // });

        $.get(purl, function(data){
            $('#modal-btn').trigger('click');
            var addresses = data.addresses;

            var shtml = '';
            for (var key in addresses) {
                var address = addresses[key];
                address = address.replace(/ ,/g, "");
                shtml += '<div class="row modal-address-row"><label class="modal-address" onclick="selectAddress(this);">' + address + '</label></div>';
            }
            $('#modal-body').html(shtml);
        })
    }

    function selectAddress(obj)
    {
        $('#modal-close').trigger('click');
        var address = obj.innerText;
        $('#address').val(address);
        $('#address').show();
    }

    function getGeoInfo()
    {
        var address = $('#address').val();
        address = address.replace(/ /g, "+");
        if (address) {
            var purl = "https://maps.googleapis.com/maps/api/geocode/json?address=" +address + "&key=AIzaSyDF_ftRKK9wLcpd7NA2AadvnJCBYB25UX4";

            $.get(purl, function(data){
                if (data.results.hasOwnProperty(0) && data.results[0].hasOwnProperty('geometry')) {
                    $('#longitude').val(data.results[0].geometry.location.lng);
                    $('#latitude').val(data.results[0].geometry.location.lat);

                    // return;
                } else {
                    // alert('Input correct address!');
                }
            });
        } else {
            // alert('Input address please!');
        }
        $('#submit-form').submit();

    }

</script>

</html>

