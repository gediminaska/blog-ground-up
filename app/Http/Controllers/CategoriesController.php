<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Session;

class CategoriesController extends Controller
{
    public function __construct(){
        $this->middleware('role:superadministrator|administrator');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('manage.categories.index')->withCategories($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
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
            'name' => 'required|min:3|max:30',
            'icon' => 'max:30'
        ]);

        $category = new Category;

        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();
        Session::flash('success', 'The category has been saved!');

        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category=Category::find($id);
        return view('manage.categories.show')->withCategory($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id){

        $this->validate($request, [
            'name' => 'required|min:3|max:30',
             'icon' => 'max:30'
        ]);
        $category=Category::find($id);
        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();
        Session::flash('success', 'The category has been updated!');
        return redirect()->route('categories.index');

    }

    public function destroy(Request $request)
    {

        $id = $request->id;
        $category = Category::find($id);

        foreach($category->posts as $post){
            $post->delete();
        }

        $category->delete();
        Session::flash('success', 'The category has been deleted!');
        return redirect()->route('categories.index');
    }
}
