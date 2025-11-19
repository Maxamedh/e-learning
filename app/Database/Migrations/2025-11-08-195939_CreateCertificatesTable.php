<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCertificatesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'certificate_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'enrollment_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'certificate_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'certificate_number' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
                'null' => true,
            ],
            'issue_date' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'expiry_date' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'verification_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'unique' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('certificate_id', true);
        $this->forge->addKey('enrollment_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('course_id');
        $this->forge->addForeignKey('enrollment_id', 'enrollments', 'enrollment_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('certificates');
    }

    public function down()
    {
        $this->forge->dropTable('certificates');
    }
}
