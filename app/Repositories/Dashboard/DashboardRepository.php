<?php
/**
 * Created by PhpStorm.
 * User: gediminask
 * Date: 18.2.6
 * Time: 10.15
 */

namespace App\Repositories\Dashboard;


interface DashboardRepository
{
    public function userLastWeekActivities($userId);


    public function systemLastWeekActivities();

    public function systemCategoryStats();
}