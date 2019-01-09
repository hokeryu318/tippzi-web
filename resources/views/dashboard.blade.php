@include('include/dash_header')
<body>

<div class="container_fluid" style="background-color: #272727;">
    <div class="stage-container">
    <div class="row">
        <div class="col-sm-4 col-xs-4">
            <a href="{{ route('index') }}">
            <img src="{{ asset('images/logo.png') }}" style="float: left; margin-top: 10%; margin-left: 20%;">
            </a>
        </div>
        <div class="col-sm-4 col-xs-1">

        </div>
        <div class="col-sm-4 col-xs-4">
            <div class="user-button" style="float: right; margin:11% 20% 0 0">
                <a href="{{ route('user.settings') }}" style="text-decoration: underline; color: white; margin-right: 15px; font-weight: bold; font-size: 19px;">Register your business here</a>
                <a href="{{ route('logout') }}"><img src="{{ asset('images/logout.png') }}" style="width: 40px;"></a>
            </div>

        </div>
    </div>
    <div class="row" style="margin-top: 30px;">
        <div class="col-sm-4" >
            <div class="row" style="background-color: #64318E; padding-left: 10%; padding-right: 10%; margin-left: 5%; margin-right: 5%; margin-top:20px; border-radius: 24px;">
                <div style="color: white; margin-top:35px; width: 100%">
                    <h4>Your deals</h4>
                </div>

                @foreach($deals as $deal)
                    <div style="background-color: white; border-radius:10px;margin-top:10px; width:100%; padding: 25px 25px 0px 25px;">
                        <h4 style="font-weight: bold; font-size: 20px;">{{ $deal->title }}</h4>
                        <h5 style="color: #999999; font-size: 18px;">{{ $deal->description }}</h5>
                        <h6 style="color:#94318E; font-size:16px; ">{{ $deal->day_string }}</h6>
                        <hr style="border-top: 1px dashed">
                        <div>
                            <i class="c-icon-time" style="width:20px;"></i>
                            <a href="{{ route('deal.edit', ['id' => $deal->Id]) }}" class="edit_deal" style="margin-bottom: 15px; width: 40%; font-size:16px; font-weight:bold; border: none; -webkit-border-radius: 17px;-moz-border-radius: 17px;border-radius: 17px; color: #ffffff; text-align: center; padding-top: 7px;">Edit deal</a>
                        </div>

                    </div>
                @endforeach

                <div style="margin-top:10px; margin-bottom:30px; width: 100%;">
                    <a href="{{ route('deal.create', ['bar_id' => $bar['Id']]) }}" class="add_deal" style="float: right; background-color: #946FB2; cursor: pointer; padding-top: 15px; padding-bottom: 15px; border: none; color: #ffffff; height: 65px; text-align:center; font-weight: bold">Add a deal</a>
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            <div class="row" style="background-color: white; margin-left:5%; margin-right: 5%; -webkit-border-radius: 24px;-moz-border-radius: 24px;border-radius: 24px; padding-left:7%; padding-right: 7%; margin-top:20px;">
                <div style="margin-top:35px; margin-bottom:20px; width: 100%;">
                    <h4 style="float: left; margin-top: 10px;">Your business profiles</h4>
                    <a href="{{ route('bar.edit', ['id' => $bar['Id']]) }}" style="float: right; margin-top: 5px; background: #64318E; color: #ffffff; border: none; width: 150px; border-radius: 27px; padding: 15px; cursor:pointer; text-align: center; font-weight: bold">Edit Profile</a>
                </div>

                <div style="margin-top:5px; margin-bottom:5px; width: 100%">
                    @if($bar['name'])
                    <img src="{{ asset('images/check.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                    <h6 style="color: #999999;width:90%;">{{ $bar['name'] }}</h6>
                    @else
                    <img src="{{ asset('images/minus.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                    <h6 style="color: #999999;width:90%;">Service Name</h6>
                    @endif
                </div>

                <div style="margin-top:5px; margin-bottom:5px; width: 100%">
                    @if($bar['address'])
                    <img src="{{ asset('images/check.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                    <h6 style="color: #999999;width:90%;">{{ $bar['address'] }}</h6>
                    @else
                        <img src="{{ asset('images/minus.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                        <h6 style="color: #999999;width:90%;">Address</h6>
                    @endif
                </div>

                <div style="margin-top:5px; margin-bottom:5px; width: 100%">
                    @if($bar['telephone'])
                    <img src="{{ asset('images/check.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                    <h6 style="color: #999999;width:90%;">{{ $bar['telephone'] }}</h6>
                        @else
                        <img src="{{ asset('images/minus.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                        <h6 style="color: #999999;width:90%;">Telephone number</h6>
                    @endif
                </div>

                <div style="margin-top:5px; margin-bottom:5px; width: 100%">
                    @if($bar['email'])
                    <img src="{{ asset('images/check.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                    <h6 style="color: #999999;width:90%;">{{ $bar['email'] }}</h6>
                        @else
                        <img src="{{ asset('images/minus.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                        <h6 style="color: #999999;width:90%;">Email address</h6>
                    @endif
                </div>

                <hr style="border-top: 1px dashed; width: 100%">

                <div style="margin-top:5px; margin-bottom:5px; width: 100%">
                    @if($bar['description'])
                    <img src="{{ asset('images/check.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                    <h6 style="color: #999999;width:90%;">{{ $bar['description'] }}</h6>
                        @else
                        <img src="{{ asset('images/minus.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                        <h6 style="color: #999999;width:90%;">Brief description</h6>
                    @endif
                </div>

                <div style="margin-top:5px; margin-bottom:5px; width: 100%">
                    @if($bar['music_type'])
                    <img src="{{ asset('images/check.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                    <h6 style="color: #999999;width:90%;">{{ $bar['music_type'] }}</h6>
                        @else
                        <img src="{{ asset('images/minus.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                        <h6 style="color: #999999;width:90%;">Type of music played</h6>
                    @endif
                </div>

                <hr style="border-top: 1px dashed; width: 100%">
                @if($time_cnt)
                    @foreach($times as $time)
                        <div style="margin-top:5px; margin-bottom:5px; width: 100%">
                            <img src="" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                            <h6 style="color: #999999;width:15%; float: left">{{$time[0]}} : </h6>
                            <h6 style="color: #999999;width:75%;">{{ $time[1] }}</h6>
                        </div>
                    @endforeach
                @else
                    <div style="margin-top:5px; margin-bottom:5px; width: 100%">
                        <img src="{{ asset('images/minus.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                        <h6 style="color: #999999;width:90%;">Opening hours</h6>
                    </div>
                @endif

                <hr style="border-top: 1px dashed; width: 100%">




                @if($image_cnt)
                    <img src="{{ asset('images/check.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                    <h6 style="color: #999999;width:90%;">Image gallery</h6>
                <div style="margin-left: 15px;">
                    @foreach($images as $image)
                        <div class="profile-img">
                            <img src="{{ $image }}" >
                        </div>
                    @endforeach
                </div>
                @else
                    <div style="margin-top:5px; margin-bottom:15px; width: 100%">
                        <img src="{{ asset('images/minus.png') }}" style="width:15px;height:15px; float: left; margin-top:3px; margin-right: 10px;">
                        <h6 style="color: #999999;width:90%;">Image gallery</h6>
                    </div>
                @endif
            </div>

        </div>
    </div>
    </div>
    @include('include.footer')
</div>

</body>
</html>




