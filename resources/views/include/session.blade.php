@if (session('success'))
    <div class="c-notice c-notice--blue" data-notice="" style="background: transparent; color: red;">
        <div class="c-notice__inner" style="padding-left: 6%; padding-top: 5px;">
            <?php echo session('success') ?>
            <span class="c-notice__close" data-notice__close="" >
                <i class="icon icon--modal__close__button"></i>
            </span>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="c-notice c-notice--red" data-notice="">
        <div class="c-notice__inner">
            <?php echo session('error') ?>
            <span class="c-notice__close" data-notice__close="">
                <i class="icon icon--modal__close__button"></i>
            </span>
        </div>
    </div>
@endif

@if($errors->has('email'))
    <span class="c-error-block">{{ $errors->first('email') }}</span>
@endif
