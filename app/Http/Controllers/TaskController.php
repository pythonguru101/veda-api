<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;

class TaskController extends Controller
{
    //

    public function add_task(Request $request, $id = null){

        $validator  =   Validator::make($request->all(), [
			'title' => 'required',
            'description' => 'required',
		]);
        if($validator->fails()){
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }
        if(empty($id)){
            $inputs = $request->all();
            $inputs['user_id'] = Auth::id();
            // dd($inputs);
            DB::table('tasks')->insertGetId($inputs);
            return response()->json(["status" => "success", "message" => "Success! Task added successfully", "data" => $inputs]);
        }else{
            $inputs = $request->all();
            DB::table('tasks')->where('id', $id)->update($inputs);
            return response()->json(["status" => "success", "message" => "Success! Task updated successfully", "data" => $inputs]);
        }
    }

    public function remove_task($id = null){
        if(empty($id)){
            DB::table('tasks')->where('user_id', Auth::id())->delete();
        }else{
            DB::table('tasks')->where('id', $id)->delete();
        }
    }
}
