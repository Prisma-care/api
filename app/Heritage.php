<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Heritage extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asset_name','asset_type', 'description', 'happened_at', 'album_id',
    ];

    public function users()
    {
        return $this->belongsToMany('App\Album');
    }
}
