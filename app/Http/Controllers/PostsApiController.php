<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Repositories\Dashboard\DashboardRepository;
use App\User;
use Illuminate\Http\Request;


class PostsApiController extends Controller
{

    public function __construct()
    {
    }


    public function apiCheckUnique(Request $request)
    {
        return json_encode(!Post::where('slug', '=', $request->slug)->exists());
    }

    public function apiGetStats(DashboardRepository $dashboardRepository)
    {
        $userActivity = $dashboardRepository->systemLastWeekActivities();
        $labels = [];
        $rows = [];

        foreach ($userActivity as $value) {
            $labels[] = $value->date;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function apiGetCategoryStats(DashboardRepository $dashboardRepository)
    {
        $categoryStats = $dashboardRepository->systemCategoryStats();
        $labels = [];
        $rows = [];

        foreach ($categoryStats as $value) {
            $labels[] = Category::query()->find($value->category)->name;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function apiGetUserStats(DashboardRepository $dashboardRepository)
    {
        $userStats = $dashboardRepository->systemUserStats();
        $labels = [];
        $rows = [];

        foreach ($userStats as $value) {
            $labels[] = User::query()->find($value->user)->name;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function apiGetCommentStats(DashboardRepository $dashboardRepository)
    {
        $commentStats = $dashboardRepository->systemCommentStats();
        $labels = [];
        $rows = [];

        foreach ($commentStats as $value) {
            $labels[] = Post::query()->find($value->post)->slug;
            $rows[] = $value->count;
        }

        $data = [
            'labels' => $labels,
            'rows' => $rows,
        ];
        return response()->json(['data' => $data], 200);
    }

}
