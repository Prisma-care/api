<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment','user_id','story_id',
    ];

    public function author()
    {
        return $this->belongsTo('App\User');
    }

    public function story()
    {
        return $this-belongsTo('App\Story');
    }
}
