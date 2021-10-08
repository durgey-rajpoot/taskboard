<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    protected $table='task';

    protected $fillable = [
        'user_id',
        'task_name',
        'description',
        'task_start_date',
        'task_end_date',
        'task_final_date',
        'status'
    ];
}