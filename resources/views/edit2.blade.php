@include('include/header')
@include('include/session')
<title>Edit Profile</title>
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
                <form action="{{ route('bar.update2') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}" />

                    <div class="row t-input-row">
                        <img src="{{ asset('images/stage2.png') }}">
                        <p>Briefly describing your business</p>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-xs-12">
                            <textarea name="description" placeholder="Briefly describing here">{{ get_template_variable('description', $bar) }}</textarea>
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
                            <input type="text" name="music_type" placeholder="Music" value="{{ get_template_variable('music_type', $bar) }}"/>
                            @if($errors->has('music_type'))
                                <span class="c-error-block">{{ $errors->first('music_type') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row t-input-row">
                        <div class="col-sm-6">
                            <button type="button" onclick="previous();" class="round_btn ">Previous</button>
                        </div>
                        <div class="col-sm-6">
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

<script>

    function previous()
    {
        location.href = "<?php echo route('bar.edit', ['id' => $id, 'step' => 1]) ?>";
    }
</script>

</html>

