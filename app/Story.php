<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Story extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'happened_at', 'file_name','albums_id', 'users_id',
    ];

    public function comments()
    {
    	return $this->hasMany('App\Comments');
    }

    public function album()
    {
    	return $this->belongsTo('App\Album');
    }
}
