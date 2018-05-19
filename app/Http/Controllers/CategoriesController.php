<?php
namespace App\Http\Controllers;

use App\Category;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

class CategoriesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', [new Category, Auth::user()]);

        $categories = Category::all();
        return view('manage.categories.index', compact('categories'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', [new Category, Auth::user()]);

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $category=Category::query()->find($id);

        $this->authorize('show', [$category, Auth::user()]);

        return view('manage.categories.show', compact('category'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function update(Request $request, $id){

        $category=Category::query()->find($id);

        $this->authorize('update', [$category, Auth::user()]);

        $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'icon' => 'max:30'
        ]);
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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $category=Category::query()->find($id);

        $this->authorize('destroy', [$category, Auth::user()]);

        $category->delete();

        $toaster = new Toaster;
        $toaster->success('The category has been deleted!');
        Cache::forget('categories');
        return redirect()->route('categories.index');
    }
}