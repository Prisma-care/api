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
        'description', 'happened_at', 'is_heritage', 'asset_name', 'asset_type', 'favorited', 'album_id', 'user_id'
    ];

    protected $casts = [
        'favorited' => 'boolean',
        'is_heritage' => 'boolean'
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
