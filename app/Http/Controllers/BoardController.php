<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Validator;


class BoardController extends Controller
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
        $board = Board::get();
        return response()->json([
            'message' => 'Board List',
            'board_list' => $board
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
            'board_name' => 'required|string|between:2,100',
            'board_description' => 'required|string|between:2,200',
            'board_start_at' => 'required|date_format:Y/m/d|after:today',
            'board_end_at' => 'required|date_format:Y/m/d|after:start_date'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $board = Board::create($request->all());

        return response()->json([
            'message' => 'Board successfully Created',
            'board' => $board
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
            'board_name' => 'required|string|between:2,100',
            'board_description' => 'required|string|between:2,100',
            'board_start_at' => 'required|date_format:Y/m/d|after:today',
            'board_end_at' => 'required|date_format:Y/m/d|after:start_date'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

       return Board::create($request->all()); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Board::where('id', $id)->exists()) {
            $board = Board::where('id', $id)->get();

           return response()->json([
            'message' => 'Board  Record By ID',
                'board' => $board
            ], 201);

          } else {
            return response()->json([
            'message' => 'Board not found'
        ], 400);
          }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Board $board)
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
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'board_name' => 'required|string|between:2,100',
            'board_description' => 'required|string|between:2,100',
            'board_start_at' => 'required|date_format:Y/m/d|after:today',
            'board_end_at' => 'required|date_format:Y/m/d|after:start_date'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        //return 'ok';
        if (Board::where('id', $id)->exists()) {
            $board = Board::find($id);

            $board->board_name = is_null($request->board_name) ? $board->board_name : $request->board_name;
            $board->board_description = is_null($request->board_description) ? $board->board_description : $request->board_description;
            $board->board_start_at = is_null($request->board_start_at) ? $board->board_start_at : $request->board_start_at;
            $board->board_end_at = is_null($request->board_end_at) ? $board->board_end_at : $request->board_end_at;
            $board->save();

            return response()->json([
                "message" => "Records id ".$id." updated successfully",
                "status"=>200
            ], 200);
        } else {
            return response()->json([
                "message" => "Records id ".$id." not found",
                "status"=>400
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
         if(Board::where('id', $id)->exists()) {
            $board = Board::find($id);
            $board->delete();

           return response()->json([
            'message' => 'Record ID-'.$id." is deleted"
            ], 201);
        } else {
            return response()->json([
                "message" => "Record ID-".$id. " is not found"
            ], 404);
        }
    }
}
