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
                <form action="{{ route('bar.store3') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}" />
                    <input type="hidden" name="step" value="{{ $step }}" />
                    <input type="hidden" name="type" value="{{ $type }}" />

                    <div class="row t-input-row">
                        <img src="{{ asset('images/stage3.png') }}">
                        <p>What are your opening hours?</p>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-2">
                            <label>Open Hours</label>
                        </div>
                        <div class="col-xs-10">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0">
                                    <div class='input-group date' id='allday_start'>
                                        <input type='text' class="form-control" id="allday_start-input" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0;">
                                    <div class='input-group date' id='allday_end'>
                                        <input type='text' class="form-control" id="allday_end-input" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row t-input-row" style="margin-bottom: 16px;">
                        <div class="col-xs-2">
                            <label></label>
                        </div>
                        <div class="col-xs-10">
                            <a onclick="allWeekDays();" style="font-size:18px; cursor: pointer; text-decoration: none;">Copy and paste this open hours for all days</a>
                        </div>

                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-2">
                            <label>Monday</label>
                        </div>
                        <div class="col-xs-9">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0">
                                    <div class='input-group date' id='mon_start'>
                                        <input type='text' class="form-control" id="mon_start-input" name="mon_start" value="{{ get_template_variable('mon_start', $reg) }}" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0;">
                                    <div class='input-group date' id='mon_end'>
                                        <input type='text' class="form-control" id="mon_end-input" name="mon_end" value="{{ get_template_variable('mon_end', $reg) }}" style="" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1" style="margin-top:20px; margin-left:-30px;">
                            <a onclick="closeDay(1);" style="font-size:18px; cursor: pointer;">Close</a>
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-2">
                            <label>Tuesday</label>
                        </div>
                        <div class="col-xs-9">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0">
                                    <div class='input-group date' id='tue_start'>
                                        <input type='text' class="form-control" id="tue_start-input"  name="tue_start" value="{{ get_template_variable('tue_start', $reg) }}" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0;">
                                    <div class='input-group date' id='tue_end'>
                                        <input type='text' class="form-control" id="tue_end-input" name="tue_end" value="{{ get_template_variable('tue_end', $reg) }}" style="" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1" style="margin-top:20px; margin-left:-30px;">
                            <a onclick="closeDay(2);" style="font-size:18px; cursor: pointer;">Close</a>
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-2">
                            <label>Wednesday</label>
                        </div>
                        <div class="col-xs-9">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0">
                                    <div class='input-group date' id='wed_start'>
                                        <input type='text' class="form-control" id="wed_start-input" name="wed_start" value="{{ get_template_variable('wed_start', $reg) }}" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0;">
                                    <div class='input-group date' id='wed_end'>
                                        <input type='text' class="form-control" id="wed_end-input" name="wed_end" value="{{ get_template_variable('wed_end', $reg) }}" style="" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1" style="margin-top:20px; margin-left:-30px;">
                            <a onclick="closeDay(3);" style="font-size:18px; cursor: pointer;">Close</a>
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-2">
                            <label>Thursday</label>
                        </div>
                        <div class="col-xs-9">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0">
                                    <div class='input-group date' id='thur_start'>
                                        <input type='text' class="form-control" id="thur_start-input" name="thur_start" value="{{ get_template_variable('thur_start', $reg) }}" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0;">
                                    <div class='input-group date' id='thur_end'>
                                        <input type='text' class="form-control" id="thur_end-input" name="thur_end" value="{{ get_template_variable('thur_end', $reg) }}" style="" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1" style="margin-top:20px; margin-left:-30px;">
                            <a onclick="closeDay(4);" style="font-size:18px; cursor: pointer;">Close</a>
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-2">
                            <label>Friday</label>
                        </div>
                        <div class="col-xs-9">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0">
                                    <div class='input-group date' id='fri_start'>
                                        <input type='text' class="form-control" id="fri_start-input" name="fri_start" value="{{ get_template_variable('fri_start', $reg) }}" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0;">
                                    <div class='input-group date' id='fri_end'>
                                        <input type='text' class="form-control" id="fri_end-input" name="fri_end" value="{{ get_template_variable('fri_end', $reg) }}" style="" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1" style="margin-top:20px; margin-left:-30px;">
                            <a onclick="closeDay(5);" style="font-size:18px; cursor: pointer;">Close</a>
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-2">
                            <label>Saturday</label>
                        </div>
                        <div class="col-xs-9">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0">
                                    <div class='input-group date' id='sat_start'>
                                        <input type='text' class="form-control" id="sat_start-input" name="sat_start" value="{{ get_template_variable('sat_start', $reg) }}" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0;">
                                    <div class='input-group date' id='sat_end'>
                                        <input type='text' class="form-control" id="sat_end-input" name="sat_end" value="{{ get_template_variable('sat_end', $reg) }}" style="" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1" style="margin-top:20px; margin-left:-30px;">
                            <a onclick="closeDay(6);" style="font-size:18px; cursor: pointer;">Close</a>
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-2">
                            <label>Sunday</label>
                        </div>
                        <div class="col-xs-9">
                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0">
                                    <div class='input-group date' id='sun_start'>
                                        <input type='text' class="form-control" id="sun_start-input" name="sun_start" value="{{ get_template_variable('sun_start', $reg) }}" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" style="margin: 8px 0;">
                                    <div class='input-group date' id='sun_end'>
                                        <input type='text' class="form-control" id="sun_end-input" name="sun_end" value="{{ get_template_variable('sun_end', $reg) }}" style="" readonly/>
                                        <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-1" style="margin-top:20px; margin-left:-30px;">
                            <a onclick="closeDay(7);" style="font-size:18px; cursor: pointer;">Close</a>
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

                </form>
            </div>
            <div class="col-lg-4 col-md-2 col-sm-2">
            </div>
        </div>
    </div>
    @include('include.footer')
</div>

</body>
<script type="text/javascript">

    $(function() {
        $('#allday_start').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });

    $(function() {
        $('#allday_end').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });

    $(function() {
        $('#mon_start').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#mon_end').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#tue_start').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#tue_end').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#wed_start').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#wed_end').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#thur_start').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#thur_end').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#fri_start').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#fri_end').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#sat_start').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#sat_end').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#sun_start').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });
    $(function() {
        $('#sun_end').datetimepicker({
            format: 'LT',
            ignoreReadonly: true
        });
    });

    var redirect_type = '{{ $type }}';
    function previous()
    {
        if (redirect_type == 1) {
            location.href = "<?php echo route('bar.admin.create', ['id' => $id, 'step' => 2]) ?>";
        } else {
            location.href = "<?php echo route('bar.create', ['id' => $id, 'step' => 2]) ?>";
        }

    }

    function allWeekDays()
    {
        var start_at = $('#allday_start-input').val();
        var end_at = $('#allday_end-input').val();

        $('#mon_start-input').val(start_at); $('#mon_end-input').val(end_at);
        $('#tue_start-input').val(start_at); $('#tue_end-input').val(end_at);
        $('#wed_start-input').val(start_at); $('#wed_end-input').val(end_at);
        $('#thur_start-input').val(start_at); $('#thur_end-input').val(end_at);
        $('#fri_start-input').val(start_at); $('#fri_end-input').val(end_at);
        $('#sat_start-input').val(start_at); $('#sat_end-input').val(end_at);
        $('#sun_start-input').val(start_at); $('#sun_end-input').val(end_at);
    }

    function closeDay(ind)
    {
        if (ind == 1) {
            $('#mon_start-input').val(''); $('#mon_end-input').val('');
        } else if (ind == 2) {
            $('#tue_start-input').val(''); $('#tue_end-input').val('');
        } else if (ind == 3) {
            $('#wed_start-input').val(''); $('#wed_end-input').val('');
        } else if (ind == 4) {
            $('#thur_start-input').val(''); $('#thur_end-input').val('');
        } else if (ind == 5) {
            $('#fri_start-input').val(''); $('#fri_end-input').val('');
        } else if (ind == 6) {
            $('#sat_start-input').val(''); $('#sat_end-input').val('');
        } else if (ind == 7) {
            $('#sun_start-input').val(''); $('#sun_end-input').val('');
        }
    }

</script>

</html>

