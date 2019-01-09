<?php

namespace App\Http\Controllers;

use App\Bar;
use App\BarGallery;
use App\BarOpenTime;
use App\RegisterTmp;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;
use DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
//use Illuminate\Support\Facades\Validator;
//use Intervention\Image as Image;
use Intervention\Image\ImageManagerStatic as Image;

class BarRegisterController extends BaseController
{

    public function adminCreate(Request $request)
    {
        $id = $request->id;
        $step = $request->step;
        $type = 1;

        return $this->businessCreate($id, $step, $type);
    }

    public function create(Request $request)
    {
        $id = $request->id;
        $step = $request->step;
        $type = 0;

        return $this->businessCreate($id, $step, $type);
    }

    protected function businessCreate($id, $step, $type) {
        $categories = ['Nightlife', 'Health & Fitness', 'Hair & Beauty'];

        if (!$step) {
            $step = 0;
        }

        if ($step == 0 || $step == 1) {
            if ($id) {
                $reg = RegisterTmp::find($id)->toArray();
                $names = explode("-", $reg['username']);
                // Get First name & Last name
                $reg['first_name'] = $names[0];
                $reg['last_name'] = $names[1];

                // Get category
                $reg['category'] = array_search($reg['category'], $categories);

            } else {
                $reg = array();
            }
            return view('stage1')->with(compact('id', 'step', 'type', 'reg', 'categories'));
        } elseif ($step == 2) {
            $reg = RegisterTmp::find($id)->toArray();
            return view('stage2')->with(compact('id', 'step', 'type', 'reg'));
        } elseif ($step == 3) {
            $reg = RegisterTmp::find($id)->toArray();

            return view('stage3')->with(compact('id', 'step', 'type', 'reg'));
        } elseif ($step == 4) {
            $reg = RegisterTmp::find($id)->toArray();
            return view('stage4')->with(compact('id', 'step', 'type', 'reg'));
        }
    }

    public function store1(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        $categories = ['Nightlife', 'Health & Fitness', 'Hair & Beauty'];

        if ($type == 1) {
            $validator = Validator::make($request->all(), [
//                'first_name' => 'required',
//                'last_name' => 'required',
//                'email' => 'required|email|unique:business_user',
                'login_name' => 'required|unique:business_user',
                'telephone' => 'required|numeric',
                'password' => 'required|confirmed',
                'category' => 'required',
                'service_name' => 'required',
                'business_name' => 'required',
                'post_code' => 'required',
                'address' => 'required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:business_user',
                'telephone' => 'required|numeric',
                'password' => 'required|confirmed',
                'category' => 'required',
                'service_name' => 'required',
                'business_name' => 'required',
                'post_code' => 'required',
                'address' => 'required'
            ]);
        }


        if($validator->fails()){
            if ($type == 1) {
                return redirect()->route('bar.admin.create', ['step' => 1, 'id' => $id])->withErrors($validator->errors())->withInput();
            } else {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

        }

        $id = $request->input('id');
        if ($id) {
            $reg_tmp = RegisterTmp::find($id);
        } else {
            $reg_tmp = new RegisterTmp();
        }

        $reg_tmp->username = $request->input('first_name') . '-' . $request->input('last_name');
        $reg_tmp->email = $request->input('email');
        // For admin create
        if ($type == 1) {
            $reg_tmp->login_name = $request->input('login_name');
        }

        $reg_tmp->telephone = $request->input('telephone');
        $reg_tmp->password = md5($request->input('password'));
        $reg_tmp->category = $categories[$request->input('category')];

        $reg_tmp->service_name = $request->input('service_name');
        $reg_tmp->name = $request->input('business_name');
        $reg_tmp->post_code = $request->input('post_code');
        $reg_tmp->address = $request->input('address');
        $reg_tmp->latitude = $request->input('latitude');
        $reg_tmp->longitude = $request->input('longitude');

        $reg_tmp->save();

        if ($type == 1) {
            return redirect()->route('bar.admin.create', ['step' => 2, 'id' => $reg_tmp->id]);
        } else {
            return redirect()->route('bar.create', ['step' => 2, 'id' => $reg_tmp->id]);
        }

    }

    public function store2(Request $request)
    {
        $type = $request->input('type');

        if ($type == 0) {
            $validator = Validator::make($request->all(), [
                'business_email' => 'required|email',
                'business_telephone' => 'required|numeric',
                'business_website' => 'required',
                'description' => 'required',
                'music_type' => 'required'
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
        }


        $id = $request->input('id');
        $reg_tmp = RegisterTmp::find($id);

        $reg_tmp->business_email = $request->input('business_email');
        $reg_tmp->business_telephone = $request->input('business_telephone');
        $reg_tmp->business_website = $request->input('business_website');
        $reg_tmp->description = $request->input('description');
        $reg_tmp->music_type = $request->input('music_type');

        $reg_tmp->save();


        if ($type == 1) {
            return redirect()->route('bar.admin.create', ['step' => 3, 'id' => $reg_tmp->id]);
        } else {
            return redirect()->route('bar.create', ['step' => 3, 'id' => $reg_tmp->id]);
        }

    }

    public function store3(Request $request)
    {
        $type = $request->input('type');

        $id = $request->input('id');

        $reg_tmp = RegisterTmp::find($id);

        if ($request->input('mon_start') && $request->input('mon_end')) {
            $reg_tmp->mon_start = $request->input('mon_start');
            $reg_tmp->mon_end = $request->input('mon_end');
        } else {
            $reg_tmp->mon_start = '';
            $reg_tmp->mon_end = '';
        }

        if ($request->input('tue_start') && $request->input('tue_end')) {
            $reg_tmp->tue_start = $request->input('tue_start');
            $reg_tmp->tue_end = $request->input('tue_end');
        } else {
            $reg_tmp->tue_start = '';
            $reg_tmp->tue_end = '';
        }

        if ($request->input('wed_start') && $request->input('wed_end')) {
            $reg_tmp->wed_start = $request->input('wed_start');
            $reg_tmp->wed_end = $request->input('wed_end');
        } else {
            $reg_tmp->wed_start = '';
            $reg_tmp->wed_end = '';
        }

        if ($request->input('thur_start') && $request->input('thur_end')) {
            $reg_tmp->thur_start = $request->input('thur_start');
            $reg_tmp->thur_end = $request->input('thur_end');
        } else {
            $reg_tmp->thur_start = '';
            $reg_tmp->thur_end = '';
        }

        if ($request->input('fri_start') && $request->input('fri_end')) {
            $reg_tmp->fri_start = $request->input('fri_start');
            $reg_tmp->fri_end = $request->input('fri_end');
        } else {
            $reg_tmp->fri_start = '';
            $reg_tmp->fri_end = '';
        }

        if ($request->input('sat_start') && $request->input('sat_end')) {
            $reg_tmp->sat_start = $request->input('sat_start');
            $reg_tmp->sat_end = $request->input('sat_end');
        } else {
            $reg_tmp->sat_start = '';
            $reg_tmp->sat_end = '';
        }

        if ($request->input('sun_start') && $request->input('sun_end')) {
            $reg_tmp->sun_start = $request->input('sun_start');
            $reg_tmp->sun_end = $request->input('sun_end');
        } else {
            $reg_tmp->sun_start = '';
            $reg_tmp->sun_end = '';
        }

        $reg_tmp->save();

        if ($type == 1) {
            return redirect()->route('bar.admin.create', ['step' => 4, 'id' => $reg_tmp->id]);
        } else {
            return redirect()->route('bar.create', ['step' => 4, 'id' => $reg_tmp->id]);
        }

    }

    public function store4(Request $request)
    {
        $type = $request->input('type');

        $stage4_request = $request->all();

        $id = $request->input('id');

        $reg = RegisterTmp::find($id);

        $user = new User();
        $user->username = $reg->username;
        $user->email = $reg->email;
        if ($type == 1) {
            $user->login_name = $reg->login_name;
        }
        $user->password = $reg->password;
        $user->telephone = $reg->telephone;
        $user->save();

        $user_id = $user->Id;

        $bar = new Bar();
        $bar->user_id = $user_id;
        $bar->name = $reg->name;
        $bar->post_code = $reg->post_code;
        $bar->address = $reg->address;
        $bar->telephone = $reg->business_telephone;
        $bar->website = $reg->business_website;
        $bar->email = $reg->business_email;
        $bar->description = $reg->description;
        $bar->music_type = $reg->music_type;
        $bar->latitude = $reg->latitude;
        $bar->longitude = $reg->longitude;
        $bar->category = $reg->category;
        $bar->service_name = $reg->service_name;
        $bar->save();

        $bar_open_time = new BarOpenTime();
        $bar_open_time->bar_id = $bar->Id;
        $bar_open_time->mon_start = $reg->mon_start;
        $bar_open_time->mon_end = $reg->mon_end;
        $bar_open_time->tue_start = $reg->tue_start;
        $bar_open_time->tue_end = $reg->tue_end;
        $bar_open_time->wed_start = $reg->wed_start;
        $bar_open_time->wed_end = $reg->wed_end;
        $bar_open_time->thur_start = $reg->thur_start;
        $bar_open_time->thur_end = $reg->thur_end;
        $bar_open_time->fri_start = $reg->fri_start;
        $bar_open_time->fri_end = $reg->fri_end;
        $bar_open_time->sat_start = $reg->sat_start;
        $bar_open_time->sat_end = $reg->sat_end;
        $bar_open_time->sun_start = $reg->sun_start;
        $bar_open_time->sun_end = $reg->sun_end;
        $bar_open_time->save();

        $bar_id = $bar->Id;
        // image upload part ===================================================================
        $destinationPath = 'Tippzi/upload/';

        $input = array();
        for ($i = 1; $i <7; $i++) {
            $imagename = 'imagename' . $i;

            if ($request->input($imagename)) {
                $imgname = $request->input($imagename);
                $pos = strpos($imgname , ".");
                $extension = substr($imgname, $pos);

                $des_imagename = $user_id."_".$bar_id."_" . $i . $extension;

                File::move($imgname, $destinationPath . $des_imagename);
//                $image->move($destinationPath, $des_imagename);
                $input['imagename' . $i] = $des_imagename;
            } else {
                $input['imagename' . $i] = '';
            }
        }

        $bar_gallery = new BarGallery();
        $bar_gallery->bar_id = $bar_id;
        $bar_gallery->background_1 = $input['imagename1'];
        $bar_gallery->background_2 = $input['imagename2'];
        $bar_gallery->background_3 = $input['imagename3'];
        $bar_gallery->background_4 = $input['imagename4'];
        $bar_gallery->background_5 = $input['imagename5'];
        $bar_gallery->background_6 = $input['imagename6'];
        $bar_gallery->save();

//        Auth::login($user);

        Auth::logout();

        return redirect()->route('login');
    }

    public function edit(Request $request)
    {
        $step = $request->step;
        $id = $request->id;
        if (!$step) {
            $step = 1;
        }

        if ($step == 1) {
            $bar = Bar::find($id)->toArray();
            return view('edit1')->with(compact('bar', 'id'));
        } elseif ($step == 2) {
            $bar = Bar::find($id)->toArray();
            return view('edit2')->with(compact('bar', 'id'));
        } elseif ($step == 3) {
            $bar_time = BarOpenTime::where('bar_id', $id)->first()->toArray();
            return view('edit3')->with(compact('bar_time', 'id'));
        } elseif ($step == 4) {
            $bar_gallery = BarGallery::where('bar_id', $id)->first();
            for ($i = 1; $i <7; $i++) {
                $imagename = 'background_' . $i;
                $full_imagename = 'full_background_' . $i;
                if ($bar_gallery->$imagename) {

                    $bar_gallery->$full_imagename = asset('Tippzi/upload/' . $bar_gallery->$imagename);
                } else {
                    $bar_gallery->$full_imagename = asset('Tippzi/upload/no-image.png');
                }
            }
            return view('edit4')->with(compact('bar_gallery', 'id'));
        }
    }

    public function update1(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'name' => 'required',
//            'post_code' => 'required',
//            'address' => 'required',
//            'email' => 'required|email',
//            'telephone' => 'required|numeric',
//            'website' => 'required',
//        ]);
//
//        if($validator->fails()){
//            return redirect()->back()->withErrors($validator->errors())->withInput();
//        }

        $id = $request->input('id');
        $bar = Bar::find($id);
        $bar->name = $request->input('name');
        $bar->post_code = $request->input('post_code');
        $bar->address = $request->input('address');
        $bar->latitude = $request->input('latitude');
        $bar->longitude = $request->input('longitude');
        $bar->email = $request->input('email');
        $bar->telephone = $request->input('telephone');
        $bar->website = $request->input('website');
        $bar->save();

        return redirect()->route('bar.edit', ['step' => 2, 'id' => $id]);

    }


    public function update2(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'description' => 'required',
//            'music_type' => 'required',
//        ]);
//
//        if($validator->fails()){
//            return redirect()->back()->withErrors($validator->errors())->withInput();
//        }

        $id = $request->input('id');
        $bar = Bar::find($id);
        $bar->description = $request->input('description');
        $bar->music_type = $request->input('music_type');
        $bar->save();

        return redirect()->route('bar.edit', ['step' => 3, 'id' => $id]);
    }

    public function update3(Request $request)
    {
        $id = $request->input('id');
        $bar_time = BarOpenTime::where('bar_id', $id)->first()->toArray();
        $bar_time_id = $bar_time['Id'];

        $bar_open_time = BarOpenTime::find($bar_time_id);

        if ($request->input('mon_start') && $request->input('mon_end')) {
            $bar_open_time->mon_start = $request->input('mon_start');
            $bar_open_time->mon_end = $request->input('mon_end');
        } else {
            $bar_open_time->mon_start = '';
            $bar_open_time->mon_end = '';
        }

        if ($request->input('tue_start') && $request->input('tue_end')) {
            $bar_open_time->tue_start = $request->input('tue_start');
            $bar_open_time->tue_end = $request->input('tue_end');
        } else {
            $bar_open_time->tue_start = '';
            $bar_open_time->tue_end = '';
        }

        if ($request->input('wed_start') && $request->input('wed_end')) {
            $bar_open_time->wed_start = $request->input('wed_start');
            $bar_open_time->wed_end = $request->input('wed_end');
        } else {
            $bar_open_time->wed_start = '';
            $bar_open_time->wed_end = '';
        }

        if ($request->input('thur_start') && $request->input('thur_end')) {
            $bar_open_time->thur_start = $request->input('thur_start');
            $bar_open_time->thur_end = $request->input('thur_end');
        } else {
            $bar_open_time->thur_start = '';
            $bar_open_time->thur_end = '';
        }

        if ($request->input('fri_start') && $request->input('fri_end')) {
            $bar_open_time->fri_start = $request->input('fri_start');
            $bar_open_time->fri_end = $request->input('fri_end');
        } else {
            $bar_open_time->fri_start = '';
            $bar_open_time->fri_end = '';
        }

        if ($request->input('sat_start') && $request->input('sat_end')) {
            $bar_open_time->sat_start = $request->input('sat_start');
            $bar_open_time->sat_end = $request->input('sat_end');
        } else {
            $bar_open_time->sat_start = '';
            $bar_open_time->sat_end = '';
        }

        if ($request->input('sun_start') && $request->input('sun_end')) {
            $bar_open_time->sun_start = $request->input('sun_start');
            $bar_open_time->sun_end = $request->input('sun_end');
        } else {
            $bar_open_time->sun_start = '';
            $bar_open_time->sun_end = '';
        }

        $bar_open_time->save();

        return redirect()->route('bar.edit', ['id' => $id, 'step' => 4]);
    }

    public function update4(Request $request)
    {
        $bar_id = $request->input('id');
        $bar = Bar::find($bar_id);
        $user_id = $bar->user_id;

//        $destinationPath = public_path('/Tippzi/upload/');
        $destinationPath = 'Tippzi/upload/';

        $bar_gallery = BarGallery::where('bar_id', $bar_id)->first();

        $input = array();
        for ($i = 1; $i <7; $i++) {
            $imagename = 'imagename' . $i;
            $background = 'background_' . $i;
            if ($request->input($imagename)) {
                if ($request->input($imagename) == $i) {
                    $input['imagename' . $i] = $bar_gallery->$background;
                    continue;
                }
                $imgname = $request->input($imagename);
                $pos = strpos($imgname , ".");
                $extension = substr($imgname, $pos);

                $des_imagename = $user_id."_".$bar_id."_" . $i . $extension;

                File::move($imgname, $destinationPath . $des_imagename);
//                $image->move($destinationPath, $des_imagename);
                $input['imagename' . $i] = $des_imagename;
            } else {
                $input['imagename' . $i] = '';
            }
        }

        $bar_gallery->background_1 = $input['imagename1'];
        $bar_gallery->background_2 = $input['imagename2'];
        $bar_gallery->background_3 = $input['imagename3'];
        $bar_gallery->background_4 = $input['imagename4'];
        $bar_gallery->background_5 = $input['imagename5'];
        $bar_gallery->background_6 = $input['imagename6'];
        $bar_gallery->save();

//        Auth::login($user);

        return redirect()->route('user.dashboard');
    }
}

