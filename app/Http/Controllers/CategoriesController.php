<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Toaster;

class CategoriesController extends Controller
{
    public function __construct(){

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $neededPermission = 'read-category';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $categories = Category::all();
        return view('manage.categories.index')->withCategories($categories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $neededPermission = 'create-category';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'icon' => 'max:30'
        ]);

        $category = new Category;

        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();
        Toaster::success('The category has been saved!');

        return redirect()->route('categories.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $neededPermission = 'read-category';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $category=Category::find($id);
        return view('manage.categories.show')->withCategory($category);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, $id){

        $neededPermission = 'update-category';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $this->validate($request, [
            'name' => 'required|min:3|max:30',
             'icon' => 'max:30'
        ]);
        $category=Category::find($id);
        $category->name = $request->name;
        $category->icon = $request->icon;
        $category->save();
        Toaster::success('The category has been updated!');
        return redirect()->route('categories.index');

    }

    public function destroy(Request $request)
    {
        $neededPermission = 'delete-category';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $id = $request->id;
        $category = Category::find($id);

        foreach($category->posts as $post){
            $post->delete();
        }

        $category->delete();
        Toaster::success('The category has been deleted!');
        return redirect()->route('categories.index');
    }
}
