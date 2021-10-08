<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Taskboardlog;
use App\Models\Taskmapping;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Board;
use Auth;



class TaskController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task = Task::get();
        return response()->json([
            'message' => 'Task List',
            'task_list' => $task
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|Integer|between:1,100',
            'task_name' => 'required|string|between:2,100',
            'description' => 'required|string|between:2,200',
            'task_start_date' => 'required|date_format:Y/m/d|after:today',
            'task_end_date' => 'required|date_format:Y/m/d|after:start_date',
            'status' => 'required|Integer|between:1,5'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        //return "ok";

        $task = Task::create($request->all());

        return response()->json([
            'message' => 'Task successfully Created',
            'status' => '200'
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|Integer|between:1,10',
            'task_name' => 'required|string|between:2,100',
            'description' => 'required|string|between:2,200',
            'task_start_date' => 'required|date_format:Y/m/d|after:today',
            'task_end_date' => 'required|date_format:Y/m/d|after:start_date',
            'status' => 'required|Integer|between:1,5'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

       return Task::create($request->all());
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Task::where('id', $id)->exists()) {
            $task = Task::where('id', $id)->get();

           return response()->json([
            'message' => 'Task record by id-'.$id,
                'task' => $task
            ], 201);

          } else {
            return response()->json([
            'message' => 'Task  id -'.$id.' is not found'
        ], 400);
          }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|Integer|between:1,100',
            'user_id' => 'required|Integer|between:1,100',
            'task_name' => 'required|string|between:2,100',
            'description' => 'required|string|between:2,200',
            'task_start_date' => 'required|date_format:Y/m/d|after:today',
            'task_end_date' => 'required|date_format:Y/m/d|after:start_date',
            'status' => 'required|Integer|between:1,5'
        ]);
        //'task_final_date'=>'date_format:Y/m/d|after:end_date',

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user_id = $request->user_id;
        if (User::where('id',$user_id)->exists()) {
            $user = User::find($id);
        }else {
            return response()->json([
                "message" => "User not found"
            ], 404);
        }

        if (Task::where('id', $id)->exists()) {
            $task = Task::find($id);
            $pervious_user = $task->user_id;
           
            $task->user_id = is_null($request->user_id) ? $task->user_id : $request->user_id;
            $task->task_name = is_null($request->task_name) ? $task->task_name : $request->task_name;
            $task->description = is_null($request->description) ? $task->description : $request->description;
            $task->task_start_date = is_null($request->task_start_date) ? $task->task_start_date : $request->task_start_date;
            $task->task_end_date = is_null($request->task_end_date) ? $task->task_end_date : $request->task_end_date;
             $task->task_final_date = is_null($request->task_final_date) ? $task->task_final_date : $request->task_final_date;
            $task->status = is_null($request->status) ? $task->status : $request->status;
            $task->save();

            //print_r($pervious_user);die;

            /********Create log for user task history************/
            if(!empty($task->user_id)){
                $log = Taskboardlog::create(array('task_id' => $id,
                'previous_user' => $pervious_user,
                'new_user' => $request->user_id,
                'created_by'=>Auth::user()->id 
                ));
            }
            /*****************************************************/
            return response()->json([
                "message" => "Records id ".$id." updated successfully",
                "status" => 200
            ], 200);
        } else {
            return response()->json([
                "message" => "Records id ".$id." not found"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //return $id;
         if(Task::where('id', $id)->exists()) {
            $task = Task::find($id);
            $task->delete();

           return response()->json([
            'message' => 'Record ID-'.$id." is deleted",
            'status' => 200
            ], 201);
        } else {
            return response()->json([
                "message" => "Record ID-".$id. " is not found",
                'status' => 400
            ], 404);
        }
    }

    public function assign(Request $request)
    {
       // return $request->all();
        $validator = Validator::make($request->all(), [
            'user_id' =>'required|Integer|between:1,50',
            'task_id' =>'required|Integer|between:1,50',
            'board_id' =>'required|Integer|between:1,50',
            'status' =>'required|Integer|between:1,5'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if(Board::where('id', $request->board_id)->exists()) {
            $board = Board::find($request->board_id);
        }else {
            return response()->json([
                "message" => "Board does not exists.",
                'status' => 400
            ], 404);
        }

        if(Task::where('id', $request->task_id)->exists()) {
           $task = Task::find($request->task_id);
        }else{
             return response()->json([
                "message" => "Task does not exists.",
                'status' => 400
            ], 404);
        }

        $taskboard = Taskmapping::create($request->all());
        return response()->json([
            'message' => 'Task board assigned to user',
            'status' => '200'
        ], 201);
    }
}
