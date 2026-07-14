<?php

namespace App\Controllers;

use App\Models\TableModel;

class TableAdmin extends BaseController
{
    protected TableModel $tableModel;

    public function __construct()
    {
        $this->tableModel = new TableModel();
    }

    // ── LIST ─────────────────────────────────────────────────────────────────
    public function index()
    {
        $tables = $this->tableModel->orderBy('number', 'ASC')->findAll();

        return view('admin/table/index', [
            'pageTitle' => 'Manajemen Meja & QR Code',
            'tables'    => $tables,
        ]);
    }

    // ── STORE ─────────────────────────────────────────────────────────────────
    public function store()
    {
        $number = (int) $this->request->getPost('number');
        $name   = trim($this->request->getPost('name') ?: 'Meja ' . $number);

        // Check unique number
        $exists = $this->tableModel->where('number', $number)->first();
        if ($exists) {
            return redirect()->to('/tables')->with('error', "Nomor meja {$number} sudah ada.");
        }

        $this->tableModel->save([
            'name'     => $name,
            'number'   => $number,
            'capacity' => (int) ($this->request->getPost('capacity') ?: 4),
            'location' => $this->request->getPost('location') ?: null,
            'is_active' => 1,
        ]);

        return redirect()->to('/tables')->with('success', "Meja {$name} berhasil ditambahkan!");
    }

    // ── UPDATE ─────────────────────────────────────────────────────────────────
    public function update(int $id)
    {
        $table = $this->tableModel->find($id);
        if (!$table) {
            return redirect()->to('/tables')->with('error', 'Meja tidak ditemukan.');
        }

        $number = (int) $this->request->getPost('number');

        // Check unique number (exclude current)
        $exists = $this->tableModel->where('number', $number)->where('id !=', $id)->first();
        if ($exists) {
            return redirect()->to('/tables')->with('error', "Nomor meja {$number} sudah digunakan.");
        }

        $this->tableModel->update($id, [
            'name'      => trim($this->request->getPost('name') ?: 'Meja ' . $number),
            'number'    => $number,
            'capacity'  => (int) ($this->request->getPost('capacity') ?: 4),
            'location'  => $this->request->getPost('location') ?: null,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('/tables')->with('success', 'Meja berhasil diperbarui!');
    }

    // ── DELETE ─────────────────────────────────────────────────────────────────
    public function delete(int $id)
    {
        $this->tableModel->delete($id);
        return redirect()->to('/tables')->with('success', 'Meja berhasil dihapus!');
    }

    // ── QR CODE — Single Table ─────────────────────────────────────────────────
    public function qr(int $id)
    {
        $table = $this->tableModel->find($id);
        if (!$table) {
            return redirect()->to('/tables')->with('error', 'Meja tidak ditemukan.');
        }

        $menuUrl = base_url('?table=' . $table['number']);

        return view('admin/table/qr_single', [
            'pageTitle' => 'QR Code — ' . $table['name'],
            'table'     => $table,
            'menuUrl'   => $menuUrl,
        ]);
    }

    // ── QR CODE — Print All ────────────────────────────────────────────────────
    public function printAll()
    {
        $tables  = $this->tableModel->where('is_active', 1)->orderBy('number', 'ASC')->findAll();
        $menuUrls = [];
        foreach ($tables as $t) {
            $menuUrls[$t['id']] = base_url('?table=' . $t['number']);
        }

        return view('admin/table/qr_print', [
            'tables'   => $tables,
            'menuUrls' => $menuUrls,
        ]);
    }
}
