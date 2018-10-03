<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\User_menu;
use App\Models\User_roles;
use App\Models\Group_menu;

class User_menuController extends Controller
{
    /* @author : Daniel Andi */

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function get_usermenu($id, $type)
    {
        // $roles = User_menu::select('menu_id', 'menu_name', 'menu_code')->where('user_id', $id)->get();
        $roles = User_roles::select('group_id')->where('user_id', $id)->get();
        $menu = Group_menu::select('menu_id', 'menu_title', 'icon')
                            ->whereIn('group_id', $roles)
                            ->where('type', $type)
                            ->where('can_read', TRUE)
                            ->orderBy('menu_id')
                            ->groupBy('menu_id', 'menu_title', 'icon')
                            ->get();

        return response()->json($menu);
    }

    public function get_cancreate($id, $type)
    {
        // $roles = User_menu::select('menu_id', 'menu_name', 'menu_code')->where('user_id', $id)->get();
        $roles = User_roles::select('group_id')->where('user_id', $id)->get();
        $menu = Group_menu::select('menu_id', 'menu_title', 'icon')
                            ->whereIn('group_id', $roles)
                            ->where('type', $type)
                            ->where('can_create', TRUE)
                            // ->where('menu_id', $menu)
                            ->orderBy('menu_id')
                            ->groupBy('menu_id', 'menu_title', 'icon')
                            ->get();

        return response()->json($menu);
    }

    public function get_canedit($id, $type)
    {
        // $roles = User_menu::select('menu_id', 'menu_name', 'menu_code')->where('user_id', $id)->get();
        $roles = User_roles::select('group_id')->where('user_id', $id)->get();
        $menu = Group_menu::select('menu_id', 'menu_title', 'icon')
                            ->whereIn('group_id', $roles)
                            ->where('type', $type)
                            ->where('can_edit', TRUE)
                            ->orderBy('menu_id')
                            ->groupBy('menu_id', 'menu_title', 'icon')
                            ->get();

        return response()->json($menu);
    }

    public function get_candelete($id, $type)
    {
        // $roles = User_menu::select('menu_id', 'menu_name', 'menu_code')->where('user_id', $id)->get();
        $roles = User_roles::select('group_id')->where('user_id', $id)->get();
        $menu = Group_menu::select('menu_id', 'menu_title', 'icon')
                            ->whereIn('group_id', $roles)
                            ->where('type', $type)
                            ->where('can_delete', TRUE)
                            ->orderBy('menu_id')
                            ->groupBy('menu_id', 'menu_title', 'icon')
                            ->get();

        return response()->json($menu);
    }
}
