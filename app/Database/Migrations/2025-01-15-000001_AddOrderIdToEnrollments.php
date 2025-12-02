<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOrderIdToEnrollments extends Migration
{
    public function up()
    {
        // Check if column doesn't exist before adding
        $fields = [
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'course_id',
            ],
        ];
        
        // Check if column exists
        if (!$this->db->fieldExists('order_id', 'enrollments')) {
            $this->forge->addColumn('enrollments', $fields);
            $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'SET NULL', 'enrollments_order_fk');
        }
    }

    public function down()
    {
        // Drop foreign key first
        if ($this->db->fieldExists('order_id', 'enrollments')) {
            $this->forge->dropForeignKey('enrollments', 'enrollments_order_fk');
            $this->forge->dropColumn('enrollments', 'order_id');
        }
    }
}

