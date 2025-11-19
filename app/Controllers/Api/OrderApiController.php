<?php

namespace App\Controllers\Api;

use App\Controllers\BaseApiController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class OrderApiController extends BaseApiController
{
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new \App\Models\OrderItemModel();
    }

    public function index()
    {
        $this->authenticate();
        $user_id = $this->request->getGet('user_id') ?? $this->currentUser['user_id'];
        
        if ($user_id !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        $orders = $this->orderModel->select('orders.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.user_id = orders.user_id')
            ->where('orders.user_id', $user_id)
            ->orderBy('orders.created_at', 'DESC')
            ->findAll();

        return $this->respond(['status' => 'success', 'data' => $orders]);
    }

    public function show($id = null)
    {
        $this->authenticate();
        $order = $this->orderModel->getOrderWithItems($id);
        if (!$order) return $this->failNotFound('Order not found');

        if ($order['user_id'] !== $this->currentUser['user_id'] && !$this->hasPermission('admin')) {
            return $this->failForbidden('Access denied');
        }

        return $this->respond(['status' => 'success', 'data' => $order]);
    }

    public function create()
    {
        $this->authenticate();
        if (!$this->requirePermission(['student', 'admin'])) return;

        $data = $this->request->getJSON(true);
        $data['user_id'] = $data['user_id'] ?? $this->currentUser['user_id'];

        helper('uuid');
        $data['order_id'] = generate_uuid();
        $data['status'] = $data['status'] ?? 'pending';
        $data['currency'] = $data['currency'] ?? 'USD';

        if ($this->orderModel->insert($data)) {
            // Create order items
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $item['order_id'] = $data['order_id'];
                    $this->orderItemModel->insert($item);
                }
            }

            return $this->respondCreated(['status' => 'success', 'message' => 'Order created', 'data' => $this->orderModel->getOrderWithItems($data['order_id'])]);
        }
        return $this->failValidationErrors($this->orderModel->errors());
    }

    public function update($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;

        $data = $this->request->getJSON(true);
        if ($this->orderModel->update($id, $data)) {
            return $this->respond(['status' => 'success', 'message' => 'Order updated', 'data' => $this->orderModel->getOrderWithItems($id)]);
        }
        return $this->failValidationErrors($this->orderModel->errors());
    }

    public function delete($id = null)
    {
        $this->authenticate();
        if (!$this->requirePermission(['admin'])) return;

        if ($this->orderModel->delete($id)) {
            return $this->respondDeleted(['status' => 'success', 'message' => 'Order deleted']);
        }
        return $this->fail('Failed to delete order');
    }
}

