<?php
namespace App\Http\Controllers;

use App\Category;
use Cache;
use Illuminate\Http\Request;
use Toaster;
use Auth;

class CategoriesController extends Controller
{
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
        Cache::forget('categories');

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
        Cache::forget('categories');
        return redirect()->route('categories.index');

    }

    public function destroy($id)
    {
        $neededPermission = 'delete-category';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $category = Category::find($id);

//
//        foreach($category->posts as $post){
//            $post->delete();
//        }
//        dd('passed permissions');
//
//
        $category->delete();

        Toaster::success('The category has been deleted!');
        Cache::forget('categories');
        return redirect()->route('categories.index');
    }
}