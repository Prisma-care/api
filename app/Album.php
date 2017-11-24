<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Album extends Model
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'patient_id', 'is_default', 'source_album_id'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function isDefault()
    {
        return $this->patient_id === null;
    }

    public function stories()
    {
        return $this->hasMany('App\Story');
    }

    public function heritage()
    {
        return $this->hasMany('App\Heritage');
    }

    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
}
