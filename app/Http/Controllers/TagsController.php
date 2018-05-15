<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2|max:20'
        ]);

        $tag = new Tag;

        $tag->setAttribute('name', $request->get('name'));

        $tag->save();

        return back();

    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        $tag=Tag::query()->find($id)->first();
        $tag->posts()->detach()->delete();
    }
}
