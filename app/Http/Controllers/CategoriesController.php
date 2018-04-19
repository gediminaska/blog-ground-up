<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Toaster;
use Cache;

class CategoriesController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {

        $this->rejectUserWhoCannot('read-category');

        $categories = Category::with('posts')->get();

        return view('manage.categories.index')->withCategories($categories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->rejectUserWhoCannot('create-category');

        $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'icon' => 'max:30'
        ]);

        $category = new Category;

        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();

        Toaster::success('The category has been saved!');
        Cache::forget('categories');

        return redirect()->route('categories.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $this->rejectUserWhoCannot('read-category');

        $category=Category::find($id);
        return view('manage.categories.show')->withCategory($category);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, $id){

        $this->rejectUserWhoCannot('update-category');

        $this->validate($request, [
            'name' => 'required|min:3|max:30',
             'icon' => 'max:30'
        ]);
        $category=Category::find($id);
        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();
        Toaster::success('The category has been updated!');
        Cache::forget('categories');
        return redirect()->route('categories.index');

    }

    public function destroy($id)
    {
        $this->rejectUserWhoCannot('delete-category');

        $category = Category::find($id);

        foreach($category->posts as $post){
            $post->delete();
        }

        $category->delete();
        Toaster::success('The category has been deleted!');
        Cache::forget('categories');
        return redirect()->route('categories.index');
    }

}
