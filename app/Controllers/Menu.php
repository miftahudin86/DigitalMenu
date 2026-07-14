<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\CategoryModel;
use App\Libraries\S3Library;

class Menu extends BaseController
{
    protected MenuModel $menuModel;
    protected CategoryModel $categoryModel;
    protected S3Library $s3;

    public function __construct()
    {
        $this->menuModel     = new MenuModel();
        $this->categoryModel = new CategoryModel();
        $this->s3            = new S3Library();
    }

    // ── LIST ─────────────────────────────────────────────────────────────────
    public function index()
    {
        $menus = $this->menuModel
            ->select('menus.*, categories.name as category_name')
            ->join('categories', 'categories.id = menus.category_id', 'left')
            ->orderBy('menus.id', 'DESC')
            ->findAll();

        return view('admin/menu/index', [
            'pageTitle' => 'Menu',
            'menus'     => $menus,
        ]);
    }

    // ── CREATE FORM ───────────────────────────────────────────────────────────
    public function create()
    {
        $categories = $this->categoryModel->findAll();
        return view('admin/menu/form', [
            'menu'       => null,
            'categories' => $categories,
            'action'     => site_url('/menu/store'),
            'pageTitle'  => 'Tambah Menu',
        ]);
    }

    // ── STORE ─────────────────────────────────────────────────────────────────
    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[2]|max_length[255]',
            'category_id' => 'required|integer',
            'price'       => 'required|decimal',
            'description' => 'permit_empty',
            'image'       => 'permit_empty|is_image[image]|max_size[image,5120]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageUrl = null;
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageUrl = $this->s3->upload($imageFile);

            // Fallback: save locally if S3 not configured
            if (!$imageUrl) {
                $newName = $imageFile->getRandomName();
                $imageFile->move(FCPATH . 'uploads/menus', $newName);
                $imageUrl = base_url('uploads/menus/' . $newName);
            }
        }

        $this->menuModel->save([
            'category_id'  => $this->request->getPost('category_id'),
            'name'         => $this->request->getPost('name'),
            'description'  => $this->request->getPost('description'),
            'price'        => $this->request->getPost('price'),
            'image_url'    => $imageUrl,
            'is_available' => $this->request->getPost('is_available') ? 1 : 0,
        ]);

        return redirect()->to('/menu')->with('success', 'Menu berhasil ditambahkan!');
    }

    // ── EDIT FORM ─────────────────────────────────────────────────────────────
    public function edit(int $id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/menu')->with('error', 'Menu tidak ditemukan.');
        }

        $categories = $this->categoryModel->findAll();
        return view('admin/menu/form', [
            'menu'       => $menu,
            'categories' => $categories,
            'action'     => site_url("/menu/update/{$id}"),
            'pageTitle'  => 'Edit Menu',
        ]);
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────
    public function update(int $id)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('/menu')->with('error', 'Menu tidak ditemukan.');
        }

        $rules = [
            'name'        => 'required|min_length[2]|max_length[255]',
            'category_id' => 'required|integer',
            'price'       => 'required|decimal',
            'description' => 'permit_empty',
            'image'       => 'permit_empty|is_image[image]|max_size[image,5120]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imageUrl = $menu['image_url'];
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Delete old image from S3
            if ($imageUrl) {
                $this->s3->delete($imageUrl);
            }

            $newUrl = $this->s3->upload($imageFile);
            if (!$newUrl) {
                $newName = $imageFile->getRandomName();
                $imageFile->move(FCPATH . 'uploads/menus', $newName);
                $newUrl = base_url('uploads/menus/' . $newName);
            }
            $imageUrl = $newUrl;
        }

        $this->menuModel->update($id, [
            'category_id'  => $this->request->getPost('category_id'),
            'name'         => $this->request->getPost('name'),
            'description'  => $this->request->getPost('description'),
            'price'        => $this->request->getPost('price'),
            'image_url'    => $imageUrl,
            'is_available' => $this->request->getPost('is_available') ? 1 : 0,
        ]);

        return redirect()->to('/menu')->with('success', 'Menu berhasil diperbarui!');
    }

    // ── DELETE ────────────────────────────────────────────────────────────────
    public function delete(int $id)
    {
        $menu = $this->menuModel->find($id);
        if ($menu) {
            if ($menu['image_url']) {
                $this->s3->delete($menu['image_url']);
            }
            $this->menuModel->delete($id);
        }
        return redirect()->to('/menu')->with('success', 'Menu berhasil dihapus!');
    }

    // ── TOGGLE AVAILABILITY ───────────────────────────────────────────────────
    public function toggleAvailable(int $id)
    {
        $menu = $this->menuModel->find($id);
        if ($menu) {
            $this->menuModel->update($id, [
                'is_available' => $menu['is_available'] ? 0 : 1,
            ]);
        }
        return redirect()->to('/menu')->with('success', 'Status menu diperbarui!');
    }
}
