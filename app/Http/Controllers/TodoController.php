<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{

    public function index(Request $request)
    {
        $query = Todo::query();

      
        if ($request->search) {
            $query->where('task', 'like', '%' . $request->search . '%');
        }

        if ($request->status == 'pending') {
            $query->where('completed', false);
        }

        if ($request->status == 'completed') {
            $query->where('completed', true);
        }

        
        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        
        if ($request->sort == 'due_date') {
            $query->orderBy('due_date');
        }

        if ($request->sort == 'priority') {
            $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low')");
        }

        $todos = $query->latest()->get();

        return view('todo', compact('todos'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required'
        ]);

        Todo::create([
            'task' => $request->task,
            'completed' => false,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium'
        ]);

        return redirect()->route('todo.index');
    }

  
   
    public function update($id)
    {
        $todo = Todo::findOrFail($id);

        $todo->update([
            'completed' => !$todo->completed
        ]);

        return redirect()->route('todo.index');
    }

  
    public function updateText(Request $request, $id)
    {
        $request->validate([
            'task' => 'required'
        ]);

        $todo = Todo::findOrFail($id);

        $todo->update([
            'task' => $request->task,
            'due_date' => $request->due_date,
            'priority' => $request->priority
        ]);

        return redirect()->route('todo.index');
    }

    
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return redirect()->route('todo.index');
    }
}
