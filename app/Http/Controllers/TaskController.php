<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\Mail\TaskNotification;
use Illuminate\Support\Facades\Mail;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Task::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'description'   => 'required|max:500',
            'notification'  => 'email'
        ]);

        $task = Task::create(['description' => request('description')]);

        if (!empty($request->notification)) {
            Mail::to($request->notification)->queue(new TaskNotification($task));
        }

        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Task::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'description'   => 'required|max:500',
            'notification'  => 'email'
        ]);

        $task = Task::findOrFail($id);
        $task->update(['description' => $request->input(['description'])]);

        if (!empty($request->notification)) {
            Mail::to($request->notification)->queue(new TaskNotification($task));
        }

        return 200;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return 204;
    }

}
