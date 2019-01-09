<?php

namespace App\Http\Controllers;

use App\Bar;
use App\BarGallery;
use App\BarOpenTime;
use App\Deal;
use App\DealDays;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
	public function top()
	{
		return view('index');
	}
    //
    public function index()
    {
        $id = Auth::user()->Id;

        $b_user = User::find($id);
        $bar = Bar::where('user_id', $id)->first();

        $bar_id = $bar->Id;

        $bar_open_time = BarOpenTime::where('bar_id', $bar_id)->first();
        $times = array();
        $time_cnt = 0;
        if ($bar_open_time['mon_start'] && $bar_open_time['mon_end']) {
            $time = $bar_open_time['mon_start'] . " - "  . $bar_open_time['mon_end'];
            $time_cnt++;
        } else {
            $time = "Closed";
        }
        $times[] = array('Monday', $time);

        if ($bar_open_time['tue_start'] && $bar_open_time['tue_end']) {
            $time = $bar_open_time['tue_start'] . " - "  . $bar_open_time['tue_end'];
            $time_cnt++;
        } else {
            $time = "Closed";
        }
        $times[] = array('Tuesday', $time);

        if ($bar_open_time['wed_start'] && $bar_open_time['wed_end']) {
            $time = $bar_open_time['wed_start'] . " - "  . $bar_open_time['wed_end'];
            $time_cnt++;
        } else {
            $time = "Closed";
        }
        $times[] = array('Wednesday', $time);

        if ($bar_open_time['thur_start'] && $bar_open_time['thur_end']) {
            $time = $bar_open_time['thur_start'] . " - "  . $bar_open_time['thur_end'];
            $time_cnt++;
        } else {
            $time = "Closed";
        }
        $times[] = array("Thursday", $time);

        if ($bar_open_time['fri_start'] && $bar_open_time['fri_end']) {
            $time = $bar_open_time['fri_start'] . " - "  . $bar_open_time['fri_end'];
            $time_cnt++;
        } else {
            $time = "Closed";
        }
        $times[] = array("Friday", $time);

        if ($bar_open_time['sat_start'] && $bar_open_time['sat_end']) {
            $time = $bar_open_time['sat_start'] . " - "  . $bar_open_time['sat_end'];
            $time_cnt++;
        } else {
            $time = "Closed";
        }
        $times[] = array("Saturday", $time);

        if ($bar_open_time['sun_start'] && $bar_open_time['sun_end']) {
            $time = $bar_open_time['sun_start'] . " - "  . $bar_open_time['sun_end'];
            $time_cnt++;
        } else {
            $time = "Closed";
        }
        $times[] = array("Sunday", $time);

        $bar_gallery = BarGallery::where('bar_id', $bar_id)->first();
        $images = array();
        $image_cnt =0;
        $desPath = 'Tippzi/upload/';
        if ($bar_gallery['background_1']) {
            $image_cnt++;
        }
        if ($bar_gallery['background_2']) {
            $image_cnt++;
        }
        if ($bar_gallery['background_3']) {
            $image_cnt++;
        }
        if ($bar_gallery['background_4']) {
            $image_cnt++;
        }
        if ($bar_gallery['background_5']) {
            $image_cnt++;
        }
        if ($bar_gallery['background_6']) {
            $image_cnt++;
        }

        $images[] = $this->getImage($bar_gallery['background_1']);
        $images[] = $this->getImage($bar_gallery['background_2']);
        $images[] = $this->getImage($bar_gallery['background_3']);
        $images[] = $this->getImage($bar_gallery['background_4']);
        $images[] = $this->getImage($bar_gallery['background_5']);
        $images[] = $this->getImage($bar_gallery['background_6']);

        $deals = Deal::where('bar_id', $bar_id)->get();

        $weekdays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
        $short_weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        foreach ($deals as $deal) {
            $deal_day = DealDays::where('deal_id', $deal->Id)->first();
            $deal_days = array();
            foreach ($weekdays as $key => $day) {
                $short_day = $short_weekdays[$key];
                if ($deal_day->$day) {
                    $deal_days[] = $short_day;
                }
            }

            $day_string = 'valid ';
            $day_string = $day_string . implode(", ", $deal_days);
            $day_string = $day_string . " ext " . $deal->duration;
            $deal->day_string = $day_string;

        }
        return view('dashboard')->with(compact('b_user', 'bar', 'times', 'time_cnt', 'images', 'image_cnt', 'deals'));
    }

    public function settings()
    {
        $id = Auth::user()->Id;
        $user = User::find($id);
        $email = $user->email;
        $login_name = $user->login_name;
        return view('settings')->with(compact('email', 'login_name'));
    }

    public function changeEmail(Request $request)
    {
        $id = Auth::user()->Id;
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:business_user',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $email = $request->input('email');
        $user->email = $email;
        $user->save();

        return redirect()->route('user.dashboard')->withSuccess('You have changed your email successfully');
    }

    public function changePassword(Request $request)
    {
        $id = Auth::user()->Id;
        $user = User::find($id);


        $validator = Validator::make($request->all(), [
            'old_password' => 'old_password',
            'password' => 'required|confirmed',
        ],[
            'old_password.old_password' => 'Invalid password'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user->password = md5($request->input('password'));
        $user->save();

        return redirect()->route('user.dashboard');
    }

    public function changeLoginname(Request $request)
    {
        $id = Auth::user()->Id;
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'login_name' => 'required|unique:business_user',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $login_name = $request->input('login_name');
        $user->login_name = $login_name;
        $user->save();

        return redirect()->route('user.dashboard')->withSuccess('You have changed your login name successfully');
    }

    protected function getImage($image_name) {
        $desPath = 'Tippzi/upload/';
        if ($image_name) {
            return asset($desPath . $image_name);
        } else {
            return asset('Tippzi/upload/no-image.png');;
        }
    }

    public function checkAdminLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if ($username == 'shazimphilips' && $password == 'tippzi2018') {
            $ret = 1;
        } else {
            $ret = 0;
        }
        $result = ['result' => $ret];
        echo json_encode($result);
    }

          public function agreement()
          {
	return view('agreement');
          }

          public function cookiePolicy()
          {
	return view('cookie-policy');
          }

      public function tcSuppliers()
          {
	return view('terms-conditions-suppliers');
          }

          public function terms()
      {
	return view('terms');          
           }
}
