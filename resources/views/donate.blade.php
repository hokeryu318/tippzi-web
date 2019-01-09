<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tippzi | Donate</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('bower_components/admin-lte/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('bower_components/admin-lte/dist/css/skins/_all-skins.min.css')}}">
</head>
<body>

<div class="wrapper">
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Donate via Paypal</h3>
                </div>
                <form class="form-horizontal" action="{{ route('donate.post') }}" method="POST">
                    <div class="box-body">
                        @if(isset($response) && $response)
                        <div class="callout callout-success">
                            <h4>Thank you for your donate!</h4>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputName" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-cash" data-amt=50>50</button>
                            <button type="button" class="btn btn-default btn-cash" data-amt=100>100</button>
                            <button type="button" class="btn btn-default btn-cash" data-amt=150>150</button>
                            <button type="button" class="btn btn-default btn-cash" data-amt=200>200</button>
                        </div>
                        <input type="hidden" name="amount" id="amount" value=50>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-warning" onclick="onClose()">Close</button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</section>
</div>

<!-- jQuery 3 -->
<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('bower_components/admin-lte/dist/js/adminlte.min.js')}}"></script>
<script src="{{ asset('js/parsley.js') }}"></script>
<script>
    $('.btn-cash').click(function(){
        var amt = $(this).data('amt');
        $('#amount').val(amt);
    });
    function onClose(){
        window.close();
    }
</script>
</body>
