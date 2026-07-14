<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\MenuModel;

class Customer extends BaseController
{
    public function submitOrder()
    {
        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();
        $menuModel = new MenuModel();

        // Expect JSON payload
        $json = $this->request->getJSON();

        if (!$json || empty($json->cart)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Keranjang kosong']);
        }

        $tableNumber = isset($json->table) ? (int)$json->table : null;
        $cart = $json->cart;

        $totalPrice = 0;
        $orderItems = [];

        foreach ($cart as $item) {
            $menu = $menuModel->find($item->id);
            if ($menu) {
                $subtotal = $menu['price'] * $item->qty;
                $totalPrice += $subtotal;
                
                $orderItems[] = [
                    'menu_id' => $menu['id'],
                    'quantity' => (int)$item->qty,
                    'price' => $menu['price'],
                    'notes' => isset($item->notes) ? $item->notes : null
                ];
            }
        }

        if (empty($orderItems)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Menu tidak valid']);
        }

        // Create Order
        $orderData = [
            'table_number' => $tableNumber,
            'total_price' => $totalPrice,
            'status' => 'Menunggu'
        ];
        $orderId = $orderModel->insert($orderData);

        // Create Order Items
        foreach ($orderItems as &$oi) {
            $oi['order_id'] = $orderId;
        }
        $orderItemModel->insertBatch($orderItems);

        // Store order_id in session so customer can track it
        session()->set('last_order_id', $orderId);

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Pesanan berhasil dibuat!',
            'order_id' => $orderId
        ]);
    }

    public function dashboard()
    {
        $orderId = session()->get('last_order_id');
        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();
        $menuModel = new MenuModel();

        $order = null;
        $items = [];

        if ($orderId) {
            $order = $orderModel->find($orderId);
            if ($order) {
                $rawItems = $orderItemModel->where('order_id', $orderId)->findAll();
                foreach ($rawItems as $ri) {
                    $menu = $menuModel->find($ri['menu_id']);
                    $ri['menu_name'] = $menu ? $menu['name'] : 'Menu dihapus';
                    $items[] = $ri;
                }
            }
        }

        return view('public/dashboard', [
            'order' => $order,
            'items' => $items
        ]);
    }
}
