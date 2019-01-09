@include('include/header')
<title>tippzi | Settings</title>
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
                <div class="row setting-row" style="margin-top: 18px;">
                    <label>
                        Your Email : {{ $email }}
                    </label>
                    <p></p>
                    <label>
                        Your Loginname : {{ $login_name }}
                    </label>
                </div>
                <form action="{{ route('user.change.email') }}" method="POST">
                    @csrf
                    <div class="row setting-row" style="margin-top: 8px;">
                        <label onclick="settingPage();">
                            <input type="checkbox" style="margin-left: 12px; margin-right: 18px;" id="chk-email" name="chk-email" value="1"/>
                            Do you want to change your Email?
                        </label>
                    </div>

                    <div class="row setting-row t-input-row" id="email-part">
                        <div class="row">
                            <div class="col-sm-9">
                                <input id="email" type="text" name="email" placeholder="Email" value="{{ old('email') }}"/>
                                @if($errors->has('email'))
                                    <span class="c-error-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="col-sm-3" style="margin-top: 5px;">
                                <button type="submit" class="round_btn">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="{{ route('user.change.password') }}" method="POST">
                    @csrf
                    <div class="row setting-row" style="margin-top: 8px;">
                        <label onclick="settingPage();">
                            <input type="checkbox" style="margin-left: 12px; margin-right: 18px;" id="chk-password" name="chk-password" value="1"/>
                            Do you want to change your Password?
                        </label>
                    </div>



                    <div class="row setting-row" id="password-part">
                        <div class="row t-input-row">
                            <div class="col-sm-9">
                                <input id="old_password" type="password" name="old_password" placeholder="Current Password" value="{{ old('old_password') }}"/>
                                @if($errors->has('old_password'))
                                    <span class="c-error-block">{{ $errors->first('old_password') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row t-input-row">
                            <div class="col-sm-9">
                                <input id="password" type="password" name="password" placeholder="New Password"/>
                                @if($errors->has('password'))
                                    <span class="c-error-block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row t-input-row">
                            <div class="col-sm-9">
                                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password"/>
                            </div>
                            <div class="col-sm-3" style="margin-top: 5px;">
                                <button type="submit" class="round_btn">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="{{ route('user.change.loginname') }}" method="POST">
                    @csrf
                    <div class="row setting-row" style="margin-top: 8px;">
                        <label onclick="settingPage();">
                            <input type="checkbox" style="margin-left: 12px; margin-right: 18px;" id="chk-loginname" name="chk-loginname" value="1"/>
                            Do you want to change your Login name?
                        </label>
                    </div>



                    <div class="row setting-row t-input-row" id="loginname-part">
                        <div class="row">
                            <div class="col-sm-9">
                                <input id="login_name" type="text" name="login_name" placeholder="New Loginname" value="{{ old('login_name') }}"/>
                                @if($errors->has('login_name'))
                                    <span class="c-error-block">{{ $errors->first('login_name') }}</span>
                                @endif
                            </div>
                            <div class="col-sm-3" style="margin-top: 5px;">
                                <button type="submit" class="round_btn">Save</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row t-input-row" style="text-align: center; margin-top: 40px; padding-left: 30px; padding-right: 30px;">
                    <button type="button" class="round_btn" onclick="previous();"> Back</button>
                </div>

            </div>
            <div class="col-lg-4 col-md-2 col-sm-2">
            </div>
        </div>
    </div>
    @include('include.footer')
</div>

</body>
<script>
    var chkEmail, chkPassword, chkLoginname;
    chkEmail = '<?php echo old('chk-email')?>';
    chkPassword = '<?php echo old('chk-password')?>';
    chkLoginname = '<?php echo old('chk-loginname')?>';
    $(document).ready(function(){
        initPage();
    });

    function initPage()
    {
        if (chkEmail == 1) {
            document.getElementById('chk-email').checked = true;
            // $('#chk-email').trigger('click');
        }
        if (chkPassword == 1) {
            document.getElementById('chk-password').checked = true;
            // $('#chk-password').trigger('click');
        }
        if (chkLoginname == 1) {
            document.getElementById('chk-loginname').checked = true;
            // $('#chk-password').trigger('click');
        }
        settingPage();
    }

    function settingPage()
    {
        var chk_email = document.getElementById('chk-email').checked;
        if (chk_email) {
            $('#email-part').show();
        } else {
            $('#email-part').hide();
        }

        var chk_password = document.getElementById('chk-password').checked;
        if (chk_password) {
            $('#password-part').show();
        } else {
            $('#password-part').hide();
        }

        var chk_loginname = document.getElementById('chk-loginname').checked;
        if (chk_loginname) {
            $('#loginname-part').show();
        } else {
            $('#loginname-part').hide();
        }

    }

    function previous()
    {
        location.href='<?php echo route("user.dashboard");?>'
    }
</script>
</html>

