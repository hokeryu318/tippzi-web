<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AjaxUploadController extends Controller
{
    //
    public function upload(Request $request)
    {

        $desPath = 'Tippzi/upload/tmp/';
//        $desPath = public_path('Tippzi/upload/tmp/');

        $id = $request->input('id');

        $image = $request->file('image');

        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            $response['id'] = $id;
            $response['status'] = -1;
        } elseif ($image->getSize() > 2097152) {
            $response['id'] = $id;
            $response['status'] = 0;
        } else {
            $filename = time() . uniqid(rand()) . '.' . $image->getClientOriginalExtension();
            $image->move($desPath, $filename);

            $response['status'] = '1';
            $response['filename'] = $filename;
            $response['id'] = $id;

//        $desPath = public_path('Tippzi/upload/tmp/');
            $desPath1 = public_path('Tippzi/upload/tmp/');

            $response['filename'] = asset($desPath . $filename);
            $response['save_filename'] = $desPath . $filename;

        }

        echo json_encode($response);
        return;

//        return response()->json($response);
    }

}
