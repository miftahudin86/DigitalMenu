<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\CategoryModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $menuModel     = new MenuModel();
        $categoryModel = new CategoryModel();

        $data = [
            'pageTitle'        => 'Dashboard',
            'total_menu'       => $menuModel->countAll(),
            'total_category'   => $categoryModel->countAll(),
            'total_available'  => $menuModel->where('is_available', 1)->countAllResults(),
            'recent_menus'     => $menuModel
                ->select('menus.*, categories.name as category_name')
                ->join('categories', 'categories.id = menus.category_id', 'left')
                ->orderBy('menus.created_at', 'DESC')
                ->limit(5)
                ->find(),
        ];

        return view('admin/dashboard', $data);
    }
}
