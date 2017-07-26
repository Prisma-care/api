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
     * Premake albums specified in $categories, fill them up with heritage data.
     * Ties into the FTUE, a patient should never have an empty story.
     *
     * @return void
     */
    public function prepopulate()
    {
        $categories = ['Sport', 'Voeding', 'Roeselare'];
        $ids = [];
        foreach ($categories as $category) {
            $category = Category::where('name', '=', $category)->first();
            $album = Album::create([
                'title' => $category->name,
                'patient_id' => $this->id
            ]);
            $heritage = $category->heritage;
            $this->createStoriesFromHeritage($heritage, $album->id);
        }
        $empties = ['Kindertijd', 'Huwelijk'];
        foreach ($empties as $emptyAlbum) {
            $album = Album::create([
                'title' => $emptyAlbum,
                'patient_id' => $this->id
            ]);
        }
    }

    private function createStoriesFromHeritage(Collection $heritageData, int $albumId)
    {
        foreach ($heritageData as $heritage) {
            $story = Story::create([
                'description' => ($heritage->description) ?: "",
                'asset_name' => env('APP_URL') . '/storage/heritage/' . $heritage->asset_name,
                'asset_type' > $heritage->asset_type,
                // TODO The user id should be user named Prisma or System
                'user_id' => 1,
                'album_id' => $albumId
            ]);
        }
    }
}
