@include('include/header')
@include('include/session')
<title>tippzi</title>
<body>

<div class="container-fluid t-container" style="min-height: 100%">
    <div class="header row">
        <div class="col-logo">
            <div class="logo">
                <a href="{{ route('index') }}">
                <img src="{{ asset('images/logo.png') }}">
                </a>
            </div>
        </div>
        <div class="col-paypal">
            <a style="color:white" onclick="onPaypal()">
                <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/PP_logo_h_100x26.png" alt="PayPal Logo"><br>
                <span>Donate</span>
            </a>
        </div>
        <div class="col-store">
            <a href="https://itunes.apple.com/app/id1373948490?mt=8&ign-mpt=uo%3D4">
                <img src="{{ asset('images/app_store.png') }}">
            </a>
            <a href="https://play.google.com/store/apps/details?id=com.application.tippzi&hl=en_US">
                <img src="{{ asset('images/google_play.png') }}">
            </a>
        </div>
    </div>


    <div>
    <div class="row">
        <div class="col-sm-3 col-md-2"></div>
        <div class="col-sm-6 col-md-8 t-phone">
        </div>
        <div class="col-sm-3 col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <p class="site-note">Digitally aligning businesses with consumers</p>
        </div>
        <div class="col-sm-3"></div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>

        <div class="col-md-6">
            <div class="col-sm-6">
                <button type="button" id="register" class="register" >Register a Business</button>
            </div>
            <div class="col-sm-6">
            <button type="button" id="login" class="login" style="margin-top:16px;">Login to a Business</button>
            </div>
        </div>
        <div class="col-md-3"></div>

    </div>
        @include('include.footer')
    </div>

</div>

</body>

<script>
    $(document).ready(function(){
        {{--$("#create").click(function () {--}}
        {{--$("#index-form").attr('action', "{{route('stage2.previous')}}");--}}
        {{--$("#index-form").submit();--}}
        {{--});--}}
        $("#register").click(function () {
            location.href = "{{ route('bar.create') }}"
        });
        $("#login").click(function () {
            location.href = "{{ route('login') }}";
        });
    });

    function onPaypal(){
        PopupCenter("{{ route('donate') }}", 'Donate', 480, 320);
    }

    function PopupCenter(url, title, w, h) {
        // Fixes dual-screen position                         Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var systemZoom = width / window.screen.availWidth;
        var left = (width - w) / 2 / systemZoom + dualScreenLeft
        var top = (height - h) / 2 / systemZoom + dualScreenTop
        var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow
        if (window.focus) newWindow.focus();
        return newWindow;
    }
</script>

</html>

