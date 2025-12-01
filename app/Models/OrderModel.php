<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number', 'user_id', 'total_amount', 'discount_amount', 
        'final_amount', 'currency', 'status', 
        'payment_method', 'payment_id'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required',
        'order_number' => 'required|is_unique[orders.order_number,id,{id}]',
        'status' => 'in_list[pending,completed,failed,refunded]',
    ];

    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateOrderNumber'];

    protected function generateOrderNumber(array $data)
    {
        if (!isset($data['data']['order_number']) || empty($data['data']['order_number'])) {
            $data['data']['order_number'] = 'ORD-' . strtoupper(uniqid());
        }
        return $data;
    }

    public function getOrderWithItems($orderId)
    {
        $order = $this->find($orderId);
        if ($order) {
            $builder = $this->db->table('order_items oi');
            $builder->select('oi.*, c.title as course_title, c.thumbnail_url');
            $builder->join('courses c', 'c.id = oi.course_id', 'left');
            $builder->where('oi.order_id', $orderId);
            $order['items'] = $builder->get()->getResultArray();
        }
        return $order;
    }

    public function getOrdersWithUser($limit = null)
    {
        $builder = $this->select('orders.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = orders.user_id', 'left')
            ->orderBy('orders.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
}
