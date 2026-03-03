<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\UserType;
use App\Services\BusinessService;
use App\Services\DepartmentService;
use App\Services\RoleService;

class CommonController extends Controller
{
    public function userModalData() {
        $userType = UserType::where('UserTypeID','!=',1)->get();
        $subMenu =Menu::whereNotIn('MenuID',['Dashboard','Users'])->with('allSubMenus')->orderBy('MenuOrder','asc')->get();
        return response()->json([
            'status' => 'success',
            'userTypes' => $userType,
            'allSubMenus' => $subMenu
        ]);
    }

    public function encode($param) {

    }

    public function decode($param) {

    }
}
