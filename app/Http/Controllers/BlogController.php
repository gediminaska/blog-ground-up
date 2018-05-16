<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Cache;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

class BlogController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $posts = $this->getPublishedPosts()
            ->paginate(5);

        $categories = $this->getCachedCategories();
        $tags = Tag::all()->pluck('name');
        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function indexFiltered()
    {
        if(!is_array(request('filter'))) {
            return redirect()->route('blog.index');
        }
        $tags = Tag::all()->pluck('name');
        $posts = $this->getPublishedPosts();
        $posts = $this->filterByTag($posts)
            ->paginate(5);

        $categories = $this->getCachedCategories();
        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    /**
     * @param $category_id
     * @return mixed
     */
    public function category($category_id)
    {

        $categories = $this->getCachedCategories();
        $tags = Tag::all()->pluck('name');
        $posts = $this->getPublishedPosts()
            ->where('category_id', $category_id)
            ->paginate(5);

        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    /**
     * @param $category_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function categoryFiltered($category_id)
    {
        if(!is_array(request('filter'))) {
            return redirect()->route('blog.category', $category_id);
        }

        $categories = $this->getCachedCategories();

        $tags = Tag::all()->pluck('name');
        $posts = $this->getPublishedPosts();
        $posts = $this->filterByTag($posts)
            ->where('category_id', $category_id)
            ->paginate(5);

        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    /**
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($slug)
    {
        $post = Post::query()->where('slug', $slug)->first();
        if ($post->status == 3) {
            return view('blog.single', compact('post'));
        }
        $toaster = new Toaster;
        $toaster->danger('Post not published');
        return redirect()->route('blog.index');
    }

    /**
     * @return mixed
     */
    public function search() {

        return view('blog.search');
    }

    /**
     * @param $posts
     * @return mixed
     */
    private function filterByTag($posts)
    {
        return $posts = $posts->whereHas('tags', function ($q) {
            $selectedTags = request('filter');
            $q->where('name', $selectedTags[0]);
            array_shift($selectedTags);
            foreach ($selectedTags as $selectedTag) {
                $q->orWhere('name', $selectedTag);
            }
        });
    }

    /**
     * @return mixed
     */
    private function getPublishedPosts()
    {
        return $posts = Post::query()->where('status', '=', 3)
            ->orderBy('published_at', 'desc');
    }

    /**
     * @return mixed
     */
    private function getCachedCategories()
    {
        $categories = Cache::remember('categories', 1440, function () {
            return $categories = Category::all();
        });
        return $categories;
    }
}
