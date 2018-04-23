<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Permission;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

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
        $permissionError = strlen($permissionText) == 0 ? Permission::query()->where('name', $neededPermission)->first()->display_name : $permissionText;
        $toaster = new Toaster;
        $toaster->danger('Sorry, you do not have permission to ' . $permissionError);
        return redirect()->route('blog.index');
    }

}

