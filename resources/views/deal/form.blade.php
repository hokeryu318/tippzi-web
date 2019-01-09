@include('include/header')
@if($id)
<title>Edit Deal</title>
@else
    <title>Add a Deal</title>
    @endif
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

    <div style="min-height: 800px;">
        <div class="row reg-content">
            <div class="col-lg-4 col-md-2 col-sm-2">
            </div>
            <div class="col-lg-4 col-md-8 col-sm-8">
                <form action="{{ route('deal.save') }}" method="POST" id="submit-form">
                    @csrf

                    <div class="row t-input-row">
                        <p>Tell us about your deal</p>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="title" placeholder="What is your deal?" value="{{ get_template_variable('title', $deal) }}"/>
                            @if($errors->has('title'))
                                <span class="c-error-block">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <textarea name="description" class="describe" placeholder="Additional info and conditions" style="padding:15px 20px 15px 20px;">{{ get_template_variable('description', $deal) }}</textarea>
                            @if($errors->has('description'))
                                <span class="c-error-block">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <div class="form-group" style=" margin-bottom: 0px;">
                                <div class='input-group date' id='duration'>
                                    <input type='text' class="form-control"  style=" width: 100%; -webkit-border-radius: 0;-moz-border-radius: 0;border-radius: 0;" name="duration" value="{{ get_template_variable('duration', $deal) }}" readonly/>
                                    <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            @if($errors->has('duration'))
                                <span class="c-error-block">{{ $errors->first('duration') }}</span>
                            @elseif($errors->has('quantity'))
                                <span class="c-error-block" style="color: black">a</span>
                            @endif
                        </div>

                        <div class="col-xs-6">
                            <input type="text" name="quantity" placeholder="Quantity" value="{{ get_template_variable('quantity', $deal, 'qty') }}">
                            @if($errors->has('quantity'))
                                <span class="c-error-block">{{ $errors->first('quantity') }}</span>
                            @elseif($errors->has('duration'))
                                <span class="c-error-block" style="color: black">a</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <p>What days is your deal valid?</p>
                    </div>
                    <div class="row t-input-row">
                        <select id="select-days" multiple="multiple">
                            <option value="monday">M</option>
                            <option value="tuesday">T</option>
                            <option value="wednesday">W</option>
                            <option value="thursday">T</option>
                            <option value="friday">F</option>
                            <option value="saturday">S</option>
                            <option value="sunday">S</option>
                        </select>
                    </div>

                    <div class="row t-input-row" style="margin-top:25px;">
                        <a onclick="selectAlldays();" style="font-size:18px; cursor: pointer; text-decoration: none;">Select all days</a>
                    </div>
                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <button type="button" onclick="previous();" style="margin-top:20px;" class="round_btn">Previous</button>
                        </div>
                        <div class="col-xs-6">
                            <button type="button" onclick="submitForm();" style="margin-top:20px;" class="round_btn">
                                @if($id)
                                    Edit a Deal
                                @else
                                    Add a Deal
                                @endif
                            </button>
                        </div>

                    </div>





                    <input type="hidden" name="id" value="{{ $id }}" />
                    <input type="hidden" name="bar_id" value="{{ $bar_id }}" />

                    <div id="days"></div>
                </form>
            </div>
            <div class="col-lg-4 col-md-2 col-sm-2">
            </div>
        </div>
    </div>
    @include('include.footer')
</div>



</body>

<script>

    function previous()
    {
        location.href = '<?php echo route('user.dashboard'); ?>'
    }

    var day_string = '<?php echo get_template_variable('days', $deal) ?>';

    var days = JSON.parse(day_string);
    function setWeekdays()
    {
        for (var key in days) {
            var day = days[key];
            $('.btn-days').each(function(){
                if($(this).attr('value') == day) {
                    $(this).addClass('active');
                }
            });
        }
    }

    function selectAlldays() {
        $('.btn-days').each(function(){
            $(this).addClass('active');
        });
    }

    $(document).ready(function () {
        $('#select-days').togglebutton();
        setWeekdays();
    });

    function submitForm() {
        var shtml = '';
        $('.active').each(function(){
            var val = $(this).val();
            shtml += '<input type="hidden" name="days[]" value="' + val + '" />';
        });
        $('#days').html(shtml);

        $('#submit-form').submit();
    }

    $(function() {
        var curDate = '{{ get_template_variable('duration', $deal) }}';
        $('#duration').datetimepicker({
            format: 'DD MMM YYYY',
            ignoreReadonly: true,
            defaultDate : curDate
        });
    });

</script>

</html>

