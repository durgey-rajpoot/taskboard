<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Board extends Model
{
    // protected $table='board';

    protected $fillable = [
        'board_name',
        'board_start_at',
        'board_end_at',
        'board_description'
    ];
}
