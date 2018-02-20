<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Toaster;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $neededPermission
     * @param string $permissionText
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function rejectUnauthorized($neededPermission, $permissionText='')
    {
        Toaster::danger("Sorry, you do not have permission '" . strlen($permissionText==0) ? $neededPermission : $permissionText. "'");
        return redirect()->route('blog.index');
    }
}
