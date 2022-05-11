<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::where('title', 'like', '%' . $request->search . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return response([
            'message' => 'Tasks retreived.',
            'data' => [
                'tasks' => $tasks,
            ]
        ], 200);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);

        return response([
            'message' => 'Task retreived.',
            'data' => [
                'task' => $task,
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'completed' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->first(),
            ], 400);
        }

        return response([
            'message' => 'Task added.',
            'data' => [
                'task' => Task::create($input)
            ]
        ], 200);
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response([
            'message' => 'Task deleted.',
        ], 200);
    }
}
