@include('include/header')
@include('include/session')
<body>
<div class="container-fluid stage_content">
    <div class="header row">
        <div class="col-logo">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}">
            </div>
        </div>
    </div>

    <div class="t-content">

    </div>
</div>


</body>

<script>
    $(document).ready(function(){
        $("#previous").click(function () {
            $("#login-form").attr('action', "{{route('index')}}");
            $("#login-form").submit();
        });
        $("#login").click(function () {
            $("#login-form").attr('action', "{{route('login.post')}}");
            $("#login-form").submit();
        });
    });
</script>

</html>

