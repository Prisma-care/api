<?php

namespace App;

use App\Album;
use App\Story;
use App\Category;
use App\Heritage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;

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

    protected $hidden = ['pivot'];

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

    /**
     * Copy albums with the is_default flag for every new patient
     * As part of the FTUE, we want every new patient to have heritage content that they can edit to their liking
     *
     * @return void
     */
    public function prepopulate()
    {
        $albums = Album::with('heritage')->get()->where('patient_id', '=', null)->values()->all();
        foreach ($albums as $album) {
            $newAlbum = $album->replicate();
            $newAlbum->patient_id = $this->id;
            $newAlbum->save();
            foreach ($album->heritage as $heritage) {
                Story::create([
                    'description' => $heritage->description,
                    'asset_name' => $heritage->asset_name ?: null,
                    'asset_type' => $heritage->asset_type ?: null,
                    'user_id' => 1,
                    'album_id' => $newAlbum->id,
                    'is_heritage' => true
                ]);
            }
        }
    }

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . " " .  ucfirst($this->last_name);
    }
}
