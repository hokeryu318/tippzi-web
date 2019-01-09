@include('include/header')
<title>Create a Profile</title>
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
                <form action="{{ route('bar.store1') }}" method="POST" id="submit-form">
                    @csrf
                    <div class="row t-input-row">
                        <img src="{{ asset('images/stage1.png') }}">
                        <p>Tell us about yourself</p>
                    </div>
                    <!--/ First Name & Last Name -->
                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <input type="text" name="first_name" placeholder="First name" value="{{ get_template_variable('first_name', $reg) }}">
                            @if($errors->has('first_name'))
                                <span class="c-error-block">{{ $errors->first('first_name') }}</span>
                            @elseif($errors->has('last_name'))
                                <span class="c-error-block" style="color: black">a</span>
                            @endif
                        </div>

                        <div class="col-xs-6">
                            <input type="text" name="last_name" placeholder="Last name" value="{{ get_template_variable('last_name', $reg) }}">
                            @if($errors->has('last_name'))
                                <span class="c-error-block">{{ $errors->first('last_name') }}</span>
                            @elseif($errors->has('first_name'))
                                <span class="c-error-block" style="color: black">a</span>
                            @endif
                        </div>
                    </div>
                    <!--/ email -->
                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="email" placeholder="Your email" value="{{ get_template_variable('email', $reg) }}"/>
                            @if($errors->has('email'))
                                <span class="c-error-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <!--/ login_name -->

                    @if($type == 1)
                        <div class="row t-input-row">
                            <div class="col-xs-12">
                                <input type="text" name="login_name" placeholder="Your Login Name" value="{{ get_template_variable('login_name', $reg) }}"/>
                                @if($errors->has('login_name'))
                                    <span class="c-error-block">{{ $errors->first('login_name') }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!--/ telephone -->
                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="telephone" placeholder="Your telephone" value="{{ get_template_variable('telephone', $reg) }}"/>
                            @if($errors->has('telephone'))
                                <span class="c-error-block">{{ $errors->first('telephone') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="password" name="password" placeholder="Your password"/>
                            @if($errors->has('password'))
                                <span class="c-error-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="password" name="password_confirmation" placeholder="Your password Confirmation"/>
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <select name="category" class="category">
                                @foreach($categories as $key=>$val)
                                    <option value="{{ $key }}"
                                            @if(get_template_variable('category', $reg) == $key) selected="selected" @endif>{{ $val }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input class="long" type="text" name="service_name" placeholder="Service or category type" value="{{ get_template_variable('service_name', $reg) }}"/>
                            @if($errors->has('service_name'))
                                <span class="c-error-block">{{ $errors->first('service_name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <p>Tell us about your business</p>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="business_name" placeholder="Business name" value="{{ get_template_variable('business_name', $reg, 'name') }}"/>
                            @if($errors->has('business_name'))
                                <span class="c-error-block">{{ $errors->first('business_name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <input type="text" name="post_code" placeholder="Post code" value="{{ get_template_variable('post_code', $reg) }}" id="postal-code">
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
                            <input id="address" name="address" style="margin-bottom:10px;" value="{{ get_template_variable('address', $reg) }}" placeholder="Enter your address"/>
                            @if($errors->has('address'))
                                <span class="c-error-block">{{ $errors->first('address') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <button type="button" id="stage1_previous" class="round_btn">Previous</button>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" id="stage1_continue" class="round_btn" onclick="getGeoInfo();">Continue</button>
                        </div>

                    </div>

                    <input type="hidden" name="latitude" id="latitude" value="{{ get_template_variable('latitude', $reg) }}"/>
                    <input type="hidden" name="longitude" id="longitude" value="{{ get_template_variable('longitude', $reg) }}"/>
                    <input type="hidden" name="id" value="{{ $id }}" />
                    <input type="hidden" name="type" value="{{ $type }}" />
                </form>

                <button id="modal-btn"  data-toggle="modal" data-target="#exampleModal" style="display: none"></button>
            </div>
            <div class="col-lg-4 col-md-2 col-sm-2">

            </div>
        </div>

            @include('include.footer')


        <input type="hidden" id="chk-admin" />
    </div>
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

<div style="display:none;">
    <div id="dlgContent">
        <p> Enter your administrator name </p>
        <input class="ajs-input" style="width: 100%;" id="inpOne" type="text"/>

        <p> Enter your administrator password </p>
        <input class="ajs-input" style="width: 100%;" id="inpTwo" type="password"/>

    </div>

</div>

</body>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.1/build/css/alertify.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.1/build/css/themes/bootstrap.min.css" />
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.1/build/alertify.min.js"></script>

<script>
    var redirect_type = '{{$type}}';
    var step = '{{$step}}';

    var retAdmin = 0;
    $(document).ready(function(){
        if (redirect_type == 1 && step == 0) {
            //window.onload = confirmLogin();
        }
        $("#show").click(function(){
            $("#address").show();
        });
        $("#stage1_previous").click(function () {
            if (redirect_type == 1) {
                location.href = "{{ route('index') }}";
            } else {
                location.href = "{{ route('index') }}";
            }

        });
    });

    function checkadminLogin() {
        var _token = $("input[name='_token']").val();


        $.ajax({
            type: 'POST',
            url: '{{ route('admin.create.login') }}',
            data: {
                _token: _token,
                username: inpOneVal,
                password: inpTwoVal,
            },
            success:function(data) {
                var ret = JSON.parse(data);
                if (ret['result'] == 0) {
                    location.reload();
                } else {
                    obj.hide();
                }
            },
        });
    }

    var inpOneVal, inpTwoVal;
    function confirmLogin() {
        var dlgContentHTML = $('#dlgContent').html();

        $('#dlgContent').html("");
        alertify.confirm(dlgContentHTML).set({onok: function(closeevent, value) {

        },onclose: function(){
                inpOneVal = $('#inpOne').val();
                inpTwoVal = $('#inpTwo').val();

            if (inpOneVal == 'shazimphilips' && inpTwoVal == 'tippzi2018') {
                $(this).hide();
            } else {
                location.reload();
            }
        }}).set('title', "Login");

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
                    $('#submit-form').submit();
                    return;
                } else {
                    alert('Input correct address!');
                }
            });
        } else {
            alert('Input address please!');
        }

    }

</script>

</html>

