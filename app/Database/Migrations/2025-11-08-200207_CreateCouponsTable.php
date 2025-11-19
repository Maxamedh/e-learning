<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        // Create coupons table
        $this->forge->addField([
            'coupon_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'discount_type' => [
                'type' => 'ENUM',
                'constraint' => ['percentage', 'fixed'],
            ],
            'discount_value' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'min_order_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'max_discount_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'usage_limit' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'used_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'valid_from' => [
                'type' => 'TIMESTAMP',
            ],
            'valid_until' => [
                'type' => 'TIMESTAMP',
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('coupon_id', true);
        $this->forge->createTable('coupons');

        // Create coupon_usage table
        $this->forge->addField([
            'usage_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'coupon_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'order_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'used_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('usage_id', true);
        $this->forge->addKey('coupon_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('order_id');
        $this->forge->addForeignKey('coupon_id', 'coupons', 'coupon_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('order_id', 'orders', 'order_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('coupon_usage');
    }

    public function down()
    {
        $this->forge->dropTable('coupon_usage');
        $this->forge->dropTable('coupons');
    }
}
