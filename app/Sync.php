<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sync extends Model
{
    protected $fillable = [
        'status',
        'model_type',
        'model_id',
        'finished_at'
    ];
}
