<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\EnrollmentModel;

class Orders extends BaseController
{
    protected $orderModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->enrollmentModel = new EnrollmentModel();
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
        
        $updateData = ['status' => $status];
        
        // If status is being changed to completed, set completed_at timestamp
        if ($status === 'completed' && $order['status'] !== 'completed') {
            $updateData['completed_at'] = date('Y-m-d H:i:s');
        }
        
        if ($this->orderModel->update($id, $updateData)) {
            // When order is completed, enrollment is automatically activated
            // because the Learn controller checks order status
            // No additional action needed as enrollment is already linked to order
            
            return redirect()->back()->with('success', 'Order status updated successfully! ' . ($status === 'completed' ? 'The student can now access the course.' : ''));
        } else {
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }
}

