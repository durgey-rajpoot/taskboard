<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Taskmapping extends Model
{
    protected $table='task_board_mapping';

    protected $fillable = [
    	'user_id',
        'task_id',
        'board_id',
        'status',
        'created_at',
        'created_at'
    ];
}