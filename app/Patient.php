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
        'firstname','lastname','date_of_birth','birth_location','location','care_house',
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
