<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;


class Post extends Model
{
    use Searchable;

    public function toSearchableArray()
    {
        $array = $this->toArray();

        if($this->status != 3) {
            $array['body'] = '';
            $array['title'] = '';
            $array['slug'] = '';
            $array['published_at'] = '2000-01-01 00:00:00';

            return $array;

        } else {
            $array['author_name'] = $this->user->name;
            $array['category_name'] = $this->category->name;
            $array['comments_count'] = count($this->comments);
            $array['comments_text'] = implode(',', $this->comments->map(function ($data) {
                return $data['body'];
            })->toArray());
            $array['tags'] = implode(',', $this->tags->map(function ($data) {
                return $data['name'];
            })->toArray());

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

}

