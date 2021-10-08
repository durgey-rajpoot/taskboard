<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Taskboardlog extends Model
{
    protected $table='task_board_log';
    protected $timestamp =false;

    protected $fillable = [
        'task_id',
        'previous_user',
        'new_user',
        'created_by',
        'created_at'
    ];
}