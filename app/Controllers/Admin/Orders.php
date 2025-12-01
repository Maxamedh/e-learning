<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class Orders extends BaseController
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        
        $builder = $this->orderModel->select('orders.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = orders.user_id', 'left');
        
        if ($status) {
            $builder->where('orders.status', $status);
        }
        
        if ($search) {
            $builder->groupStart()
                ->like('orders.order_number', $search)
                ->orLike('users.first_name', $search)
                ->orLike('users.last_name', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }
        
        $orders = $builder->orderBy('orders.created_at', 'DESC')->findAll();
        
        $data = [
            'title' => 'Orders Management',
            'orders' => $orders,
            'status' => $status,
            'search' => $search,
        ];
        
        return view('admin/orders/index', $data);
    }

    public function view($id)
    {
        $order = $this->orderModel->getOrderWithItems($id);
        
        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Order not found.');
        }
        
        $data = [
            'title' => 'Order Details',
            'order' => $order,
        ];
        
        return view('admin/orders/view', $data);
    }

    public function updateStatus($id)
    {
        $order = $this->orderModel->find($id);
        
        if (!$order) {
            return redirect()->to('admin/orders')->with('error', 'Order not found.');
        }
        
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['pending', 'completed', 'failed', 'refunded'])) {
            return redirect()->back()->with('error', 'Invalid status.');
        }
        
        if ($this->orderModel->update($id, ['status' => $status])) {
            return redirect()->back()->with('success', 'Order status updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }
}

