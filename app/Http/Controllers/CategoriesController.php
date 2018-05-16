<?php
namespace App\Http\Controllers;

use App\Category;
use Cache;
use Illuminate\Http\Request;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

class CategoriesController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if ($this->userCannot('read-category')) {
            return redirect()->route('blog.index');
        }
        $categories = Category::all();
        return view('manage.categories.index', compact('categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($this->userCannot('create-category')) {
            return redirect()->route('blog.index');
        }
        $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'icon' => 'max:30'
        ]);

        $category = new Category;

        $category->setAttribute('name', $request->get('name'));
        $category->setAttribute('icon', $request->get('icon'));
        $category->save();
        $toaster = new Toaster;
        $toaster->success('The category has been saved!');
        Cache::forget('categories');

        return redirect()->route('categories.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        if ($this->userCannot('read-category')) {
            return redirect()->route('blog.index');
        }
        $category=Category::query()->find($id);
        return view('manage.categories.show', compact('category'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, $id){

        if ($this->userCannot('update-category')) {
            return redirect()->route('blog.index');
        }

        $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'icon' => 'max:30'
        ]);
        $category=Category::query()->find($id);
        $category->setAttribute('name', $request->get('name'));
        $category->setAttribute('icon', $request->get('icon'));
        $category->save();
        $toaster = new Toaster;
        $toaster->success('The category has been updated!');
        Cache::forget('categories');
        return redirect()->route('categories.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if ($this->userCannot('delete-category')) {
            return redirect()->route('blog.index');
        }
        Category::query()->find($id)->delete();

        $toaster = new Toaster;
        $toaster->success('The category has been deleted!');
        Cache::forget('categories');
        return redirect()->route('categories.index');
    }
}