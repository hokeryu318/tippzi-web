<?php

namespace App\Http\Controllers;

use App\Deal;
use App\DealDays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    //
    public function create(Request $request)
    {
        $bar_id = $request->bar_id;
        $deal = array();
        $id = 0;
        $deal['days'] = array();
        return view('deal.form')->with(compact('deal', 'id', 'bar_id'));
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $deal = Deal::find($id);
        $bar_id = 0;

        $deal_day = DealDays::where('deal_id', $id)->first();
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $days = array();
        foreach ($weekdays as $key=>$weekday) {
            if ($deal_day->$weekday) {
                $days[$key] = $weekday;
            }
        }
        $deal->days = $days;

        return view('deal.form')->with(compact('deal', 'id', 'bar_id'));
    }

    public function save(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'duration' => 'required',
            'quantity' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $id = $request->id;
        if ($id) {
            $deal = Deal::find($id);
        } else {
            $deal = new Deal();
            $deal->bar_id = $request->input('bar_id');
        }
        $deal->title = $request->input('title');
        $deal->description = $request->input('description');
        $deal->duration = $request->input('duration');
        $deal->qty = $request->input('quantity');
        $deal->save();

        if ($id) {
            $deal_days = DealDays::where('deal_id', $id)->first();
        } else {
            $deal_days = new DealDays();
            $deal_days->deal_id = $deal->Id;
        }

        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $days = $request->input('days');

        foreach ($weekdays as $day) {
            $deal_days->$day = 0;
        }

        if (is_array($days)) {
            foreach ($days as $day) {
                if ($day) {
                    $deal_days->$day = 1;
                }
            }

        }

        $deal_days->save();

        return redirect()->route('user.dashboard');

    }
}
