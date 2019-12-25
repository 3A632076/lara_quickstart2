<?php

namespace App\Http\Controllers;
use App\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    /**
     * 建立一個新的控制器實例。
     *
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');
        $this->tasks = $tasks;
    }

    public function index(Request $request)
    {

        $tasks = Task::where('user_id', $request->user()->id)->get();

        return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);

        return redirect('/tasks');
    }

    /**
     * 移除給定的任務。
     *
     * @param Request $request
     * @param Task $task
     * @return Response
     */
    public function destroy(Request $request, Task $task)
    {
        $this->authorize('destroy', $task);

        // 刪除該任務...
        $task->delete();

        return redirect('/tasks');
    }
}
