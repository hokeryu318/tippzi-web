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
                <form action="{{ route('bar.store2') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}" />
                    <input type="hidden" name="step" value="{{ $step }}" />
                    <input type="hidden" name="type" value="{{ $type }}" />

                    <div class="row t-input-row">
                        <img src="{{ asset('images/stage2.png') }}">
                        <p>Tell us about your business</p>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="business_email" placeholder="Your business email" value="{{ get_template_variable('business_email', $reg) }}"/>
                            @if($errors->has('business_email'))
                                <span class="c-error-block">{{ $errors->first('business_email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="business_telephone" placeholder="Your business telephone" value="{{ get_template_variable('business_telephone', $reg) }}"/>
                            @if($errors->has('business_telephone'))
                                <span class="c-error-block">{{ $errors->first('business_telephone') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="business_website" placeholder="Your business website" value="{{ get_template_variable('business_website', $reg) }}"/>
                            @if($errors->has('business_website'))
                                <span class="c-error-block">{{ $errors->first('business_website') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <textarea name="description" placeholder="Briefly describing here">{{ get_template_variable('description', $reg) }}</textarea>
                            @if($errors->has('description'))
                                <span class="c-error-block">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>



                    <div class="row t-input-row">
                        <p>What type music do you play?</p>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <input type="text" name="music_type" placeholder="Music" value="{{ get_template_variable('music_type', $reg) }}"/>
                            @if($errors->has('music_type'))
                                <span class="c-error-block">{{ $errors->first('music_type') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row t-input-row">
                        <div class="col-xs-6">
                            <button type="button" onclick="previous();" class="round_btn">Previous</button>
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
        @include('include.footer')
    </div>

</div>

</body>

<script>

    var redirect_type = '{{ $type }}';
    function previous()
    {
        if (redirect_type == 1) {
            location.href = "<?php echo route('bar.admin.create', ['id' => $reg['id'], 'step' => 1]) ?>";
        } else {
            location.href = "<?php echo route('bar.create', ['id' => $reg['id'], 'step' => 1]) ?>";
        }

    }
</script>

</html>

