<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Todo;
use App\Events\TodoCreated;
use App\Events\TodoUpdated;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $todo = Todo::create([
            'task' => $request->task,
            'is_completed' => false,
        ]);

        broadcast(new TodoCreated($todo));

        return response()->json($todo);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);
        $todo->is_completed = $request->is_completed;
        $todo->save();

        broadcast(new TodoUpdated($todo));

        return response()->json($todo);
    }
}
