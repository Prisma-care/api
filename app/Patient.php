<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'birth_place', 'location', 'care_home'
    ];

    public function albums()
    {
        return $this->hasMany('App\Album');
    }

    public function relations()
    {
        return $this->belongsToMany('App\Relation');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
