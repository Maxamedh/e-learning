<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'order_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id', 'user_id', 'total_amount', 'discount_amount', 
        'tax_amount', 'final_amount', 'currency', 'status', 
        'payment_gateway', 'payment_intent_id', 'completed_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'total_amount' => 'float',
        'discount_amount' => 'float',
        'tax_amount' => 'float',
        'final_amount' => 'float',
        'completed_at' => 'datetime',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    protected $validationRules = [
        'user_id' => 'required',
        'status' => 'in_list[pending,completed,failed,refunded]',
    ];

    protected $skipValidation = false;

    public function getOrderWithItems($orderId)
    {
        $order = $this->find($orderId);
        if ($order) {
            $builder = $this->db->table('order_items oi');
            $builder->select('oi.*, c.title as course_title, c.thumbnail_url');
            $builder->join('courses c', 'c.course_id = oi.course_id', 'left');
            $builder->where('oi.order_id', $orderId);
            $order['items'] = $builder->get()->getResultArray();
        }
        return $order;
    }
}
