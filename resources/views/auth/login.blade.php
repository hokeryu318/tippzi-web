@include('include/header')
<title>tippzi | Login</title>
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

    <div style="min-height: 750px">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="row reg-content">
                <div class="col-sm-4">
                </div>
                <div class="col-sm-4" style="padding-top: 80px;">
                    <div class="row t-input-row">
                        <p style="margin-bottom: 20px;">Login email and password</p>
                    </div>
                    <div class="row t-input-row" style="margin-bottom: 12px">
                        <div class="col-xs-12">
                            <input id="email" type="text" name="email" placeholder="Email"/>
                            @if($errors->has('email'))
                                <span class="c-error-block">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                    </div>

                    <div class="row t-input-row" style="margin-bottom: 12px">
                        <div class="col-xs-12">
                            <input id="pass" type="password" name="password" placeholder="Password"/>
                            @if($errors->has('password'))
                                <span class="c-error-block">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <button type="button" onclick="previous();" class="round_btn">Previous</button></td>
                        </div>
                        <div class="col-xs-6">
                            <button type="submit" class="round_btn">Continue</button>
                        </div>

                    </div>

                </div>
                <div class="col-sm-4">
                </div>
            </div>
        </form>
    </div>
    @include('include.footer')
</div>
</body>
<script>

    function previous() {
        location.href = '<?php echo route('index');?>'
    }
</script>
</html>

