<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Album extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'profile_id',
    ];

    public function stories()
    {
        return $this->hasMany('App\Story');
    }

    public function albums()
    {
        return $this->hasMany('App\Heritage');
    }
    
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
}
