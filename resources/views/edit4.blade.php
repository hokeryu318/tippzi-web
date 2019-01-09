@include('include/header')
@include('include/session')
<title>Edit Profile</title>
<body>

<div class="container-fluid stage_content">
    <div id="pageloader">
        <img src="{{ asset('images/loading.gif') }}" alt="processing..." />
    </div>

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
                <form action="{{ route('bar.update4') }}" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}" />

                    <div class="row t-input-row">
                        <img src="{{ asset('images/stage4.png') }}">
                        <p>Image upload</p>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                            <div class="upload-img" id="upload-img-1">
                                <div class="upload-img-image">

                                    <img src="{{ $bar_gallery->full_background_1 }}">
                                    <input type="hidden" name="imagename1" id="imagename1" value="@if($bar_gallery->background_1) 1 @endif"/>
                                </div>
                                <div>
                                    <button type="button" class="upload_button c-upload"> Upload</button>
                                    <button type="button" class="upload_button c-remove" onclick="remove(1)"> Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                            <div class="upload-img" id="upload-img-2">
                                <div class="upload-img-image">

                                    <img src="{{ $bar_gallery->full_background_2 }}">
                                    <input type="hidden" name="imagename2" id="imagename2" value="@if($bar_gallery->background_2) 2 @endif"/>
                                </div>
                                <div>
                                    <button type="button" class="upload_button c-upload"> Upload</button>
                                    <button type="button" class="upload_button c-remove" onclick="remove(2)"> Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                            <div class="upload-img" id="upload-img-3">
                                <div class="upload-img-image">

                                    <img src="{{ $bar_gallery->full_background_3 }}">
                                    <input type="hidden" name="imagename3" id="imagename3" value="@if($bar_gallery->background_3) 3 @endif"/>
                                </div>
                                <div>
                                    <button type="button" class="upload_button c-upload"> Upload</button>
                                    <button type="button" class="upload_button c-remove" onclick="remove(3)"> Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                            <div class="upload-img" id="upload-img-4">
                                <div class="upload-img-image">

                                    <img src="{{ $bar_gallery->full_background_4 }}">
                                    <input type="hidden" name="imagename4" id="imagename4" value="@if($bar_gallery->background_4) 4 @endif"/>
                                </div>
                                <div>
                                    <button type="button" class="upload_button c-upload"> Upload</button>
                                    <button type="button" class="upload_button c-remove" onclick="remove(4)"> Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                            <div class="upload-img" id="upload-img-5">
                                <div class="upload-img-image">

                                    <img src="{{ $bar_gallery->full_background_5 }}">
                                    <input type="hidden" name="imagename5" id="imagename5" value="@if($bar_gallery->background_5) 5 @endif"/>
                                </div>
                                <div>
                                    <button type="button" class="upload_button c-upload"> Upload</button>
                                    <button type="button" class="upload_button c-remove" onclick="remove(5)"> Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                            <div class="upload-img" id="upload-img-6">
                                <div class="upload-img-image">

                                    <img src="{{ $bar_gallery->full_background_6 }}">
                                    <input type="hidden" name="imagename6" id="imagename6" value="@if($bar_gallery->background_6) 6 @endif"/>
                                </div>
                                <div>
                                    <button type="button" class="upload_button c-upload"> Upload</button>
                                    <button type="button" class="upload_button c-remove" onclick="remove(6)"> Remove</button>
                                </div>
                            </div>
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

<script>
    function previous() {
        location.href = "<?php echo route('bar.edit', ['step' =>3, 'id' => $id]);?>";
    }

    $(document).ready(function () {
        var _token = $("input[name='_token']").val();
        var div_id;
        for (var i = 1; i <  7; i++) {
            div_id = 'upload-img-' + i;
            var obj = $('#' + div_id);
            var button = $('.c-upload', obj);

            new AjaxUpload(button, {
                action : '{{ route('ajax.upload') }}',
                name : 'image',
                data : {
                    'id' : i,
                    '_token' : _token
                },
                onSubmit: function(file, response) {
                    this.disable();
                    $("#pageloader").fadeIn();
                },
                onComplete: function(file, response) {
                    $("#pageloader").hide();
                    var ret = JSON.parse(response);
                    this.enable();

                    var id = ret['id'];
                    var filename = ret['filename'];
                    var save_filename = ret['save_filename'];
                    var obj = $('#upload-img-' + id);
                    var img = $('img', obj);

                    if (ret['status'] == 1) {

                        img.attr('src', filename);
                        $('#imagename' + id).val(save_filename);
                    } else if(ret['status'] == 0) {
                        alert('Please select the file is smaller than 2MB');
                        img.attr('src', '{{ asset('Tippzi/upload/no-image.png') }}');
                    } else if(ret['status'] == -1) {

                        alert('Please upload image file');
                        img.attr('src', '{{ asset('Tippzi/upload/no-image.png') }}');
                    }

                    // if (ret['status']) {
                    //     var id = ret['id'];
                    //     var filename = ret['filename'];
                    //     var save_filename = ret['save_filename'];
                    //     var obj = $('#upload-img-' + id);
                    //     var img = $('img', obj);
                    //     img.attr('src', filename);
                    //     $('#imagename' + id).val(save_filename);
                    // }
                }
            })
        }
    })

    function remove(ind)
    {
        var no_image = '{{ asset('Tippzi/upload/no-image.png') }}';

        var obj = $('#upload-img-' + ind);
        var img = $('img', obj);
        img.attr('src', no_image);
        $('#imagename' + ind).val('');
    }
</script>

</html>

