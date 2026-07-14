<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Category extends BaseController
{
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categories = $this->categoryModel->orderBy('id', 'DESC')->findAll();
        return view('admin/category/index', [
            'pageTitle'  => 'Kategori',
            'categories' => $categories,
        ]);
    }

    public function store()
    {
        $name = trim($this->request->getPost('name'));
        if (!$name) {
            return redirect()->to('/category')->with('error', 'Nama kategori tidak boleh kosong.');
        }

        $this->categoryModel->save(['name' => $name]);
        return redirect()->to('/category')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(int $id)
    {
        $name = trim($this->request->getPost('name'));
        if (!$name) {
            return redirect()->to('/category')->with('error', 'Nama kategori tidak boleh kosong.');
        }

        $this->categoryModel->update($id, ['name' => $name]);
        return redirect()->to('/category')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function delete(int $id)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('/category')->with('success', 'Kategori berhasil dihapus!');
    }
}
