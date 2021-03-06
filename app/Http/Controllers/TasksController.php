<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Task;    // 追加
use Auth;    // 追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$tasks = task::all();
        if( \Auth::check()){
            $tasks = task::where('user_id', \Auth::user()->id)->get();// userid
            
            
        }else{
            $tasks = task::all();
        }
        return view('tasks.index',['tasks'=>$tasks,]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( \Auth::check()){
            //add validation
            $this->validate($request, [
                'status' => 'required|max:10',
                'content' => 'required|max:255',
            ]);
            $task = new task;
            $task->status = $request->status;    // 追加
            $task->content = $request->content;
            //$user = Auth::user();
            $task->user_id = \Auth::user()->id;
            $task->save();
        }
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
       $task = Task::find($id);
        if (\Auth::user()->id != $task->user_id) {
            return redirect('/');
        }

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        if (\Auth::user()->id != $task->user_id) {
            return redirect('/');
        }

        return view('tasks.edit', [
            'task' => $task,
        ]);
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
        //
        $task = Task::find($id);
        if (\Auth::user()->id != $task->user_id) {
            return redirect('/');
        }
        $this->validate($request, [
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
         //$task = Task::find($id);
         $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $task = \App\Task::find($id);
        if (\Auth::user()->id === $task->user_id) {
            $task->delete();
            
        }
        return redirect('/');
        //$task = Task::find($id);
        //$task->delete();
        //return redirect('/');
    }
}
