<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'enrollment_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'enrolled_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'enrollment_type' => [
                'type' => 'ENUM',
                'constraint' => ['free', 'paid', 'trial'],
                'default' => 'free',
            ],
            'completion_status' => [
                'type' => 'ENUM',
                'constraint' => ['in_progress', 'completed', 'dropped'],
                'default' => 'in_progress',
            ],
            'certificate_issued' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'certificate_issued_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('enrollment_id', true);
        $this->forge->addUniqueKey(['user_id', 'course_id']);
        $this->forge->addKey('user_id');
        $this->forge->addKey('course_id');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enrollments');
    }

    public function down()
    {
        $this->forge->dropTable('enrollments');
    }
}
