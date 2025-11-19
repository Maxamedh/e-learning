<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssignmentsTable extends Migration
{
    public function up()
    {
        // Create assignments table
        $this->forge->addField([
            'assignment_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'course_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'instructions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'max_score' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 100,
            ],
            'due_date' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'allow_late_submissions' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'max_file_size' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 10,
            ],
            'allowed_file_types' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on update' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('assignment_id', true);
        $this->forge->addKey('course_id');
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assignments');

        // Create assignment_submissions table
        $this->forge->addField([
            'submission_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'primary' => true,
            ],
            'assignment_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'user_id' => [
                'type' => 'CHAR',
                'constraint' => 36,
            ],
            'submission_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'file_url' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
            ],
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'submitted_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'grade' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'graded_by' => [
                'type' => 'CHAR',
                'constraint' => 36,
                'null' => true,
            ],
            'graded_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['submitted', 'graded', 'late'],
                'default' => 'submitted',
            ],
        ]);

        $this->forge->addKey('submission_id', true);
        $this->forge->addKey('assignment_id');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('assignment_id', 'assignments', 'assignment_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('graded_by', 'users', 'user_id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('assignment_submissions');
    }

    public function down()
    {
        $this->forge->dropTable('assignment_submissions');
        $this->forge->dropTable('assignments');
    }
}
