<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::where('user_id', Auth::id())->get();

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks
        ]);
    }

    public function add_task(Request $request, $id = null)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        if (empty($id)) {
            $inputs = $request->all();
            $inputs['user_id'] = Auth::id();
            $task = Task::create($inputs);

            return response()->json([
                "status" => "success",
                "message" => "Success! Task added successfully",
                "data" => $task
            ]);
        } else {
            $inputs = $request->all();
            Task::where('id', $id)->update($inputs);
            $inputs['id'] = $id;

            return response()->json([
                "status" => "success",
                "message" => "Success! Task updated successfully",
                "data" => $inputs
            ]);
        }
    }

    public function remove_task($id = null)
    {
        $task = Task::where('user_id', Auth::id());

        if (!empty($id)) {
            $task->where('id', $id);
        }

        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Success! Task Deleted',
            'data' => null
        ]);
    }
}
