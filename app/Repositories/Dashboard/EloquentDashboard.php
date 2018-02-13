<?php
/**
 * Created by PhpStorm.
 * User: gediminask
 * Date: 18.2.6
 * Time: 10.16
 */

namespace App\Repositories\Dashboard;

use DB;
use Illuminate\Support\Carbon;


class EloquentDashboard implements DashboardRepository
{
    private function baseActivityQuery() {
        $select = [
            DB::raw('count(id) AS count, DATE_FORMAT(created_at, "%Y %M") AS date')
        ];
        $query = DB::table('posts');
        $query->select($select);
        $query->where('created_at', '>=', Carbon::today()->subDays(365));
        $query->where('created_at', '<=', Carbon::today()->subDays(-1));
        $query->groupBy('date');


        return $query;
    }

    public function userLastWeekActivities($userId)
    {
        $query = $this->baseActivityQuery();
        $query->where('user_id', $userId);
        return $query->get();
    }


    public function systemLastWeekActivities()
    {
        $query = $this->baseActivityQuery();
        return $query->get();
    }

  public function systemCategoryStats()
    {
        $select = [
            DB::raw('count(id) AS count, category_id AS category')
        ];

        $query = DB::table('posts');
        $query->select($select);
        $query->groupBy('category');
        return $query->get();
    }

  public function systemUserStats()
    {
        $select = [
            DB::raw('count(id) AS count, user_id AS user')
        ];

        $query = DB::table('posts');
        $query->select($select);
        $query->groupBy('user');
        $query->orderBy('count', 'desc');
        return $query->get();
    }

    public function systemCommentStats()
    {
        $select = [
            DB::raw('count(id) AS count, post_id AS post')
        ];

        $query = DB::table('comments');
        $query->select($select);
        $query->groupBy('post');
        $query->orderBy('count', 'desc');
        return $query->get();
    }
}