<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskNotification;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    public function index()
    {
        $tasks = Task::all();
        return view('personel.task.index', compact('tasks'));
    }

    public function detail($id)
    {
        $task = Task::find($id);
        return view('personel.task.detail', compact('task'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "berkas" => "file|mimes:pdf,jpg,jpeg,png",
            "deskripsi" => "required"
        ]);

        if ($request->hasFile("berkas")) {
            $file = $request->file('berkas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $berkas = "/storage/" . $file->storeAs('files', $fileName, 'public');
        } else {
            $request->validate([
                "url" => "required"
            ]);
            $berkas = $request->url;
        }

        $task = Task::create([
            "deskripsi" => $request->deskripsi,
            "berkas" => $berkas,            
        ]);

        $users = User::all();
        foreach ($users as $user){
        TaskNotification::create([
            "user_id" => $user->id,
            "task_id" => $task->id,            
        ]);
    }

        return redirect()->route("task");
    }
}
