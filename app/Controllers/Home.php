<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\CategoryModel;
use App\Models\TableModel;

class Home extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();
        $menuModel     = new MenuModel();
        $tableModel    = new TableModel();

        $categories = $categoryModel->findAll();

        // Attach available menus to each category
        foreach ($categories as &$cat) {
            $cat['menus'] = $menuModel
                ->where('category_id', $cat['id'])
                ->where('is_available', 1)
                ->findAll();
        }

        // Detect table from QR scan
        $tableNumber = (int) $this->request->getGet('table');
        $tableInfo   = null;
        if ($tableNumber > 0) {
            $tableInfo = $tableModel->where('number', $tableNumber)->where('is_active', 1)->first();
        }

        return view('public/menu', [
            'categories' => $categories,
            'tableInfo'  => $tableInfo,
        ]);
    }
}
