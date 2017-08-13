<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Relation extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function patients()
    {
        return $this->belongsToMany('App\Patient');
    }
}
