<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Post extends Model
{
    use Searchable;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user', function($builder) {
           $builder->with('user');
        });
        static::addGlobalScope('category', function($builder) {
           $builder->with('category');
        });
        static::addGlobalScope('commentCount', function($builder) {
           $builder->withCount('comments');
        });
        static::addGlobalScope('images', function($builder) {
           $builder->with('images');
        });
        static::addGlobalScope('tags', function($builder) {
           $builder->with('tags');
        });
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        if($this->status != 3) {
            $array = [];
            return $array;

        } else {
            $array['user.name'] = $this->user->name;
            $array['category.name'] = $this->category->name;
            $array['comments_count'] = count($this->comments);
            $array['comments_text'] = implode(',', $this->comments->map(function ($data) {
                return $data['body'];
            })->toArray());
            $array['text_length'] = strlen($this->body) > 3000 ? 'Very long' : (strlen($this->body) > 1600 ? 'Long' : (strlen($this->body) > 800 ? 'Medium' : 'Short'));

            return $array;
        }
    }

    protected $dates = [
        'published_at'];

    public function category(){
        return $this->belongsTo('App\Category');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function tags(){
        return $this->belongsToMany('App\Tag');
    }
    public function comments(){
        return $this->hasMany('App\Comment');
    }
    public function images(){
        return $this->hasMany('App\Image');
    }

}

